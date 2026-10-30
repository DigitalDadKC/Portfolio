<script setup lang="ts">
import { computed, reactive, ref, watch } from "vue";
import { Filter, X } from "lucide-vue-next";

type Row = Record<string, unknown>;
type Agg = "sum" | "avg" | "count" | "distinct" | "min" | "max";

const props = defineProps<{
    /** Array of flat row objects, e.g. [{ Region: "East", Sales: 120 }, ...] */
    rows: Row[];
}>();

const COUNT_FIELD = "__record_count__";
const BLANK = "(blank)";
const SEP = "\u0000";

/* ---------- helpers ---------- */

const isBlank = (v: unknown) =>
    v === null || v === undefined || (typeof v === "string" && v.trim() === "");

const labelOf = (v: unknown) => (isBlank(v) ? BLANK : String(v).trim());

const toNumber = (v: unknown): number | null => {
    if (typeof v === "number") return Number.isFinite(v) ? v : null;
    if (typeof v !== "string") return null;
    const cleaned = v.replace(/[$€£,%\s]/g, "");
    if (cleaned === "") return null;
    const n = Number(cleaned);
    return Number.isFinite(n) ? n : null;
};

const naturalSort = (a: string, b: string) =>
    a.localeCompare(b, undefined, { numeric: true, sensitivity: "base" });

const numberFormat = new Intl.NumberFormat(undefined, { maximumFractionDigits: 2 });
const fmt = (n: number | null | undefined) =>
    n === null || n === undefined ? "—" : numberFormat.format(n);

const AGG_LABELS: Record<Agg, string> = {
    sum: "Sum",
    avg: "Average",
    count: "Count",
    distinct: "Distinct count",
    min: "Min",
    max: "Max",
};

/* ---------- field discovery ---------- */

const fields = computed<string[]>(() => {
    const seen = new Set<string>();
    for (const row of props.rows.slice(0, 500)) {
        for (const key of Object.keys(row ?? {})) seen.add(key);
    }
    return [...seen];
});

const numericFields = computed(() => {
    const result = new Set<string>();
    const sample = props.rows.slice(0, 500);
    for (const f of fields.value) {
        let filled = 0;
        let numeric = 0;
        for (const row of sample) {
            if (isBlank(row[f])) continue;
            filled++;
            if (toNumber(row[f]) !== null) numeric++;
        }
        if (filled > 0 && numeric / filled >= 0.8) result.add(f);
    }
    return result;
});

/* ---------- pivot configuration ---------- */

const rowField = ref("");
const colField = ref("");
const valueField = ref(COUNT_FIELD);
const agg = ref<Agg>("count");
const sortBy = ref<"label" | "value">("label");
const heatmap = ref(true);

const filterFields = ref<string[]>([]);
const excluded = reactive<Record<string, string[]>>({});
const search = reactive<Record<string, string>>({});

watch(
    fields,
    (list) => {
        if (!list.length) return;
        if (!list.includes(rowField.value)) {
            rowField.value =
                list.find((f) => !numericFields.value.has(f)) ?? list[0];
        }
        if (colField.value && !list.includes(colField.value)) colField.value = "";
        if (valueField.value !== COUNT_FIELD && !list.includes(valueField.value)) {
            valueField.value = COUNT_FIELD;
        }
        filterFields.value = filterFields.value.filter((f) => list.includes(f));
    },
    { immediate: true },
);

const isNumericValue = computed(
    () => valueField.value !== COUNT_FIELD && numericFields.value.has(valueField.value),
);

const availableAggs = computed<Agg[]>(() => {
    if (valueField.value === COUNT_FIELD) return ["count"];
    if (isNumericValue.value) return ["sum", "avg", "count", "distinct", "min", "max"];
    return ["count", "distinct"];
});

watch(valueField, (next, prev) => {
    if (!availableAggs.value.includes(agg.value)) agg.value = "count";
    // Switching from "Record count" to a numeric column: Sum is the usual intent.
    if (prev === COUNT_FIELD && isNumericValue.value) agg.value = "sum";
});

/* ---------- filters ---------- */

const uniqueValues = computed(() => {
    const map: Record<string, string[]> = {};
    for (const f of filterFields.value) {
        const set = new Set<string>();
        for (const row of props.rows) set.add(labelOf(row[f]));
        map[f] = [...set].sort(naturalSort);
    }
    return map;
});

const toggleFilterField = (field: string) => {
    if (filterFields.value.includes(field)) {
        filterFields.value = filterFields.value.filter((f) => f !== field);
        delete excluded[field];
        delete search[field];
    } else {
        filterFields.value = [...filterFields.value, field];
        excluded[field] = [];
        search[field] = "";
    }
};

const isIncluded = (field: string, value: string) =>
    !(excluded[field] ?? []).includes(value);

const toggleValue = (field: string, value: string) => {
    const list = excluded[field] ?? [];
    excluded[field] = list.includes(value)
        ? list.filter((v) => v !== value)
        : [...list, value];
};

const selectAll = (field: string) => {
    excluded[field] = [];
};

const clearAll = (field: string) => {
    excluded[field] = [...(uniqueValues.value[field] ?? [])];
};

const visibleValues = (field: string) => {
    const q = (search[field] ?? "").trim().toLowerCase();
    const all = uniqueValues.value[field] ?? [];
    return q ? all.filter((v) => v.toLowerCase().includes(q)) : all;
};

const filterSummary = (field: string) => {
    const total = uniqueValues.value[field]?.length ?? 0;
    const hidden = excluded[field]?.length ?? 0;
    return hidden === 0 ? "All" : `${total - hidden} of ${total}`;
};

const resetFilters = () => {
    for (const f of filterFields.value) excluded[f] = [];
};

const hasActiveFilters = computed(() =>
    filterFields.value.some((f) => (excluded[f]?.length ?? 0) > 0),
);

const filteredRows = computed(() => {
    const active = filterFields.value
        .filter((f) => (excluded[f]?.length ?? 0) > 0)
        .map((f) => ({ field: f, hidden: new Set(excluded[f]) }));
    if (!active.length) return props.rows;
    return props.rows.filter((row) =>
        active.every(({ field, hidden }) => !hidden.has(labelOf(row[field]))),
    );
});

/* ---------- aggregation ---------- */

const aggregate = (values: unknown[]): number | null => {
    const kind = agg.value;
    if (kind === "count") {
        return valueField.value === COUNT_FIELD
            ? values.length
            : values.filter((v) => !isBlank(v)).length;
    }
    if (kind === "distinct") {
        return new Set(values.filter((v) => !isBlank(v)).map((v) => String(v))).size;
    }
    const nums = values.map(toNumber).filter((n): n is number => n !== null);
    if (!nums.length) return null;
    switch (kind) {
        case "sum":
            return nums.reduce((a, b) => a + b, 0);
        case "avg":
            return nums.reduce((a, b) => a + b, 0) / nums.length;
        case "min":
            return Math.min(...nums);
        case "max":
            return Math.max(...nums);
    }
    return null;
};

const pivot = computed(() => {
    const rKey = rowField.value;
    const cKey = colField.value;
    const valKey = valueField.value;

    const cellBuckets = new Map<string, unknown[]>();
    const rowBuckets = new Map<string, unknown[]>();
    const colBuckets = new Map<string, unknown[]>();
    const grandBucket: unknown[] = [];

    const push = (map: Map<string, unknown[]>, key: string, v: unknown) => {
        const list = map.get(key);
        if (list) list.push(v);
        else map.set(key, [v]);
    };

    for (const row of filteredRows.value) {
        const rk = rKey ? labelOf(row[rKey]) : "Total";
        const ck = cKey ? labelOf(row[cKey]) : "";
        const v = valKey === COUNT_FIELD ? 1 : row[valKey];
        push(cellBuckets, rk + SEP + ck, v);
        push(rowBuckets, rk, v);
        push(colBuckets, ck, v);
        grandBucket.push(v);
    }

    const cells = new Map<string, number | null>();
    for (const [k, list] of cellBuckets) cells.set(k, aggregate(list));
    const rowTotals = new Map<string, number | null>();
    for (const [k, list] of rowBuckets) rowTotals.set(k, aggregate(list));
    const colTotals = new Map<string, number | null>();
    for (const [k, list] of colBuckets) colTotals.set(k, aggregate(list));

    let rowKeys = [...rowBuckets.keys()];
    const colKeys = cKey ? [...colBuckets.keys()].sort(naturalSort) : [""];

    if (sortBy.value === "value") {
        rowKeys.sort((a, b) => (rowTotals.get(b) ?? -Infinity) - (rowTotals.get(a) ?? -Infinity));
    } else {
        rowKeys.sort(naturalSort);
    }

    let max = 0;
    for (const v of cells.values()) if (v !== null && v > max) max = v;

    return {
        rowKeys,
        colKeys,
        cells,
        rowTotals,
        colTotals,
        grand: aggregate(grandBucket),
        max,
    };
});

const cellValue = (rk: string, ck: string) => pivot.value.cells.get(rk + SEP + ck) ?? null;

const heatStyle = (v: number | null) => {
    if (!heatmap.value || v === null || pivot.value.max <= 0 || v <= 0) return undefined;
    const ratio = Math.min(1, v / pivot.value.max);
    return { backgroundColor: `rgb(16 185 129 / ${(0.05 + ratio * 0.3).toFixed(3)})` };
};

const valueLabel = computed(() =>
    valueField.value === COUNT_FIELD
        ? "Record count"
        : `${AGG_LABELS[agg.value]} of ${valueField.value}`,
);

const wideWarning = computed(() => pivot.value.colKeys.length > 40);

const selectClass =
    "h-9 w-full rounded-md border bg-background px-3 text-sm outline-none focus-visible:ring-2 focus-visible:ring-ring disabled:opacity-60";
const labelClass = "mb-1 block text-xs font-medium text-muted-foreground";
</script>

<template>
    <section class="space-y-5 text-left" aria-label="Pivot table">
        <!-- Configuration -->
        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
            <div>
                <label :class="labelClass" for="pivot-rows">Rows (group by)</label>
                <select id="pivot-rows" v-model="rowField" :class="selectClass">
                    <option value="">None (single total)</option>
                    <option v-for="f in fields" :key="f" :value="f">{{ f }}</option>
                </select>
            </div>

            <div>
                <label :class="labelClass" for="pivot-cols">Columns (split by)</label>
                <select id="pivot-cols" v-model="colField" :class="selectClass">
                    <option value="">None</option>
                    <option
                        v-for="f in fields"
                        :key="f"
                        :value="f"
                        :disabled="f === rowField"
                    >
                        {{ f }}
                    </option>
                </select>
            </div>

            <div>
                <label :class="labelClass" for="pivot-value">Analyze column</label>
                <select id="pivot-value" v-model="valueField" :class="selectClass">
                    <option :value="COUNT_FIELD">Record count</option>
                    <option v-for="f in fields" :key="f" :value="f">
                        {{ f }}{{ numericFields.has(f) ? " (number)" : "" }}
                    </option>
                </select>
            </div>

            <div>
                <label :class="labelClass" for="pivot-agg">Summarize by</label>
                <select
                    id="pivot-agg"
                    v-model="agg"
                    :class="selectClass"
                    :disabled="availableAggs.length === 1"
                >
                    <option v-for="a in availableAggs" :key="a" :value="a">
                        {{ AGG_LABELS[a] }}
                    </option>
                </select>
            </div>
        </div>

        <!-- Filters -->
        <div class="rounded-lg border bg-muted/20 p-3">
            <div class="flex flex-wrap items-center gap-2">
                <span class="flex items-center gap-1.5 text-sm font-medium">
                    <Filter class="size-4 text-muted-foreground" />
                    Filter by
                </span>
                <button
                    v-for="f in fields"
                    :key="f"
                    type="button"
                    :aria-pressed="filterFields.includes(f)"
                    class="rounded-full border px-3 py-1 text-xs transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"
                    :class="
                        filterFields.includes(f)
                            ? 'border-emerald-600 bg-emerald-600 text-white'
                            : 'bg-background text-muted-foreground hover:bg-muted'
                    "
                    @click="toggleFilterField(f)"
                >
                    {{ f }}
                </button>
            </div>

            <div v-if="filterFields.length" class="mt-3 flex flex-wrap items-start gap-3">
                <details
                    v-for="f in filterFields"
                    :key="f"
                    class="group relative"
                >
                    <summary
                        class="flex h-9 cursor-pointer list-none items-center gap-2 rounded-md border bg-background px-3 text-sm hover:bg-muted"
                    >
                        <span class="font-medium">{{ f }}</span>
                        <span
                            class="text-muted-foreground"
                            :class="{ 'text-emerald-700': filterSummary(f) !== 'All' }"
                        >
                            {{ filterSummary(f) }}
                        </span>
                    </summary>

                    <div
                        class="absolute left-0 z-20 mt-1 w-64 rounded-md border bg-background p-2 shadow-lg"
                    >
                        <input
                            v-model="search[f]"
                            type="search"
                            placeholder="Search values"
                            class="mb-2 h-8 w-full rounded border bg-background px-2 text-sm outline-none focus-visible:ring-2 focus-visible:ring-ring"
                        />
                        <div class="mb-2 flex gap-3 text-xs">
                            <button type="button" class="text-emerald-700 hover:underline" @click="selectAll(f)">
                                Select all
                            </button>
                            <button type="button" class="text-emerald-700 hover:underline" @click="clearAll(f)">
                                Clear all
                            </button>
                        </div>
                        <ul class="max-h-56 space-y-0.5 overflow-auto">
                            <li v-for="val in visibleValues(f)" :key="val">
                                <label
                                    class="flex cursor-pointer items-center gap-2 rounded px-1.5 py-1 text-sm hover:bg-muted"
                                >
                                    <input
                                        type="checkbox"
                                        class="size-4 accent-emerald-600"
                                        :checked="isIncluded(f, val)"
                                        @change="toggleValue(f, val)"
                                    />
                                    <span class="truncate" :title="val">{{ val }}</span>
                                </label>
                            </li>
                            <li
                                v-if="!visibleValues(f).length"
                                class="px-1.5 py-2 text-xs text-muted-foreground"
                            >
                                No matching values
                            </li>
                        </ul>
                    </div>
                </details>

                <button
                    v-if="hasActiveFilters"
                    type="button"
                    class="flex h-9 items-center gap-1 text-sm text-muted-foreground hover:text-foreground"
                    @click="resetFilters"
                >
                    <X class="size-3.5" /> Reset filters
                </button>
            </div>
            <p v-else class="mt-2 text-xs text-muted-foreground">
                Choose one or more columns to turn them into filters.
            </p>
        </div>

        <!-- Table toolbar -->
        <div class="flex flex-wrap items-center justify-between gap-3 text-sm">
            <p class="text-muted-foreground">
                <span class="font-medium text-foreground">{{ valueLabel }}</span>
                · {{ filteredRows.length.toLocaleString() }} of
                {{ rows.length.toLocaleString() }} records
            </p>
            <div class="flex items-center gap-4">
                <label class="flex items-center gap-2 text-xs text-muted-foreground">
                    Sort rows
                    <select v-model="sortBy" class="h-8 rounded-md border bg-background px-2 text-xs">
                        <option value="label">A–Z</option>
                        <option value="value">Largest total first</option>
                    </select>
                </label>
                <label class="flex items-center gap-2 text-xs text-muted-foreground">
                    <input v-model="heatmap" type="checkbox" class="size-4 accent-emerald-600" />
                    Heatmap
                </label>
            </div>
        </div>

        <p v-if="wideWarning" class="text-xs text-amber-700">
            This column has many distinct values, so the table is very wide. Try a column
            with fewer categories.
        </p>

        <!-- Pivot table -->
        <div class="max-h-[50vh] overflow-auto rounded-lg border">
            <table v-if="filteredRows.length" class="w-full border-collapse text-sm">
                <thead class="sticky top-0 z-10 bg-muted">
                    <tr>
                        <th
                            class="sticky left-0 z-10 min-w-40 bg-muted px-3 py-2 text-left font-semibold"
                        >
                            {{ rowField || "Total" }}
                            <span v-if="colField" class="font-normal text-muted-foreground">
                                / {{ colField }}
                            </span>
                        </th>
                        <template v-if="colField">
                            <th
                                v-for="ck in pivot.colKeys"
                                :key="ck"
                                class="whitespace-nowrap px-3 py-2 text-right font-semibold"
                            >
                                {{ ck }}
                            </th>
                            <th class="whitespace-nowrap border-l px-3 py-2 text-right font-semibold">
                                Total
                            </th>
                        </template>
                        <th v-else class="whitespace-nowrap px-3 py-2 text-right font-semibold">
                            {{ valueLabel }}
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="rk in pivot.rowKeys" :key="rk" class="border-t">
                        <th
                            class="sticky left-0 bg-background px-3 py-1.5 text-left font-medium"
                        >
                            {{ rk }}
                        </th>
                        <template v-if="colField">
                            <td
                                v-for="ck in pivot.colKeys"
                                :key="ck"
                                class="px-3 py-1.5 text-right tabular-nums"
                                :style="heatStyle(cellValue(rk, ck))"
                            >
                                {{ fmt(cellValue(rk, ck)) }}
                            </td>
                            <td class="border-l bg-muted/30 px-3 py-1.5 text-right font-semibold tabular-nums">
                                {{ fmt(pivot.rowTotals.get(rk)) }}
                            </td>
                        </template>
                        <td
                            v-else
                            class="px-3 py-1.5 text-right tabular-nums"
                            :style="heatStyle(cellValue(rk, ''))"
                        >
                            {{ fmt(cellValue(rk, "")) }}
                        </td>
                    </tr>
                </tbody>

                <tfoot v-if="rowField || colField" class="sticky bottom-0 bg-muted">
                    <tr class="border-t-2">
                        <th class="sticky left-0 bg-muted px-3 py-2 text-left font-semibold">
                            Grand total
                        </th>
                        <template v-if="colField">
                            <td
                                v-for="ck in pivot.colKeys"
                                :key="ck"
                                class="px-3 py-2 text-right font-semibold tabular-nums"
                            >
                                {{ fmt(pivot.colTotals.get(ck)) }}
                            </td>
                        </template>
                        <td
                            :class="[
                                'px-3 py-2 text-right font-semibold tabular-nums',
                                colField ? 'border-l' : '',
                            ]"
                        >
                            {{ fmt(pivot.grand) }}
                        </td>
                    </tr>
                </tfoot>
            </table>

            <p v-else class="p-8 text-center text-sm text-muted-foreground">
                No records match the current filters.
                <button type="button" class="text-emerald-700 hover:underline" @click="resetFilters">
                    Reset filters
                </button>
            </p>
        </div>
    </section>
</template>
