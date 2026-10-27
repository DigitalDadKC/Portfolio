vue
<script setup lang="ts">
import { ref, computed, shallowRef, nextTick, useTemplateRef } from "vue";
import { Head } from "@inertiajs/vue3";
import { Input } from "@/components/ui/input";
import { Search, Layers3, FolderTree, FileText, ChevronRight, Command, X } from "lucide-vue-next";
import GuestLayout from "@/layouts/GuestLayout.vue";
import { useFuse } from "@vueuse/integrations/useFuse";
import { refDebounced } from "@vueuse/core";
import Panel from "./partials/Panel.vue";
import Header from "./partials/Header.vue";
import Current from "./partials/Current.vue";

interface Division {
    id: number;
    code: string;
    name: string;
}

interface Section {
    id: number;
    code: string;
    name: string;
    csi_division_id: number;
}

interface Subsection {
    id: number;
    code: string;
    name: string;
    csi_section_id: number;
}

type SearchItem = {
    type: "division" | "section" | "subsection";
    id: number;
    name: string;
    fullCode: string;
};

const props = defineProps<{
    divisions: Division[];
    sections: Section[];
    subsections: Subsection[];
}>();

const divisionById = new Map(props.divisions.map((d) => [d.id, d]));
const sectionById = new Map(props.sections.map((s) => [s.id, s]));
const subsectionById = new Map(props.subsections.map((s) => [s.id, s]));

const div = ref<Division | null>(props.divisions[0] ?? null);
const section = ref<Section | null>(null);
const subsection = ref<Subsection | null>(null);

const searchInput = useTemplateRef("searchInput");
const secSection = useTemplateRef("sectionSection");

const sectionsByDivision = new Map<number, Section[]>();
props.sections.forEach((s) => {
    if (!sectionsByDivision.has(s.csi_division_id)) {
        sectionsByDivision.set(s.csi_division_id, []);
    }
    sectionsByDivision.get(s.csi_division_id)!.push(s);
});

const subsectionsBySection = new Map<number, Subsection[]>();
props.subsections.forEach((s) => {
    if (!subsectionsBySection.has(s.csi_section_id)) {
        subsectionsBySection.set(s.csi_section_id, []);
    }
    subsectionsBySection.get(s.csi_section_id)!.push(s);
});

const searchItems: SearchItem[] = [
    ...props.divisions.map((d) => ({
        type: "division" as const,
        id: d.id,
        name: d.name,
        fullCode: d.code,
    })),
    ...props.sections.map((s) => ({
        type: "section" as const,
        id: s.id,
        name: s.name,
        fullCode: `${divisionById.get(s.csi_division_id)?.code ?? ""} ${s.code}`.trim(),
    })),
    ...props.subsections.map((s) => {
        const sec = sectionById.get(s.csi_section_id);
        return {
            type: "subsection" as const,
            id: s.id,
            name: s.name,
            fullCode: `${divisionById.get(sec?.csi_division_id ?? -1)?.code ?? ""} ${sec?.code ?? ""} ${s.code}`.trim(),
        };
    }),
];

const search = shallowRef("");
const debouncedSearch = refDebounced(search, 150);

const { results } = useFuse(debouncedSearch, searchItems, {
    fuseOptions: {
        keys: [
            { name: "name", weight: 0.7 },
            { name: "fullCode", weight: 0.3 },
        ],
        threshold: 0.3,
        ignoreLocation: true,
        minMatchCharLength: 2,
    },
    resultLimit: 20,
    matchAllWhenSearchEmpty: false,
});

const sections = computed(() =>
    div.value ? sectionsByDivision.get(div.value.id) ?? [] : [],
);

const subsections = computed(() =>
    section.value ? subsectionsBySection.get(section.value.id) ?? [] : [],
);

const filteredResults = computed(() =>
    search.value.trim() ? results.value : [],
);

const activePath = computed(() => [
    div.value,
    section.value,
    subsection.value,
].filter(Boolean).length);

const selectSection = (sec: Section) => {
    section.value = sec;
    subsection.value = null;
};

const selectDivision = (division: Division) => {
    div.value = division;
    section.value = sectionsByDivision.get(division.id)?.[0] ?? null;
    subsection.value = null;

    if (secSection.value) {
        secSection.value.scrollTop = 0;
    }
};

const selectSearch = async ({ item }: { item: SearchItem }) => {
    if (item.type === "division") {
        const selectedDivision = divisionById.get(item.id);
        if (!selectedDivision) return;

        div.value = selectedDivision;
        section.value =
            sectionsByDivision.get(selectedDivision.id)?.[0] ?? null;
        subsection.value = null;
    } else if (item.type === "section") {
        const selectedSection = sectionById.get(item.id);
        if (!selectedSection) return;

        section.value = selectedSection;
        div.value = divisionById.get(selectedSection.csi_division_id) ?? null;
        subsection.value = null;
    } else {
        const selectedSubsection = subsectionById.get(item.id);
        if (!selectedSubsection) return;

        subsection.value = selectedSubsection;
        section.value =
            sectionById.get(selectedSubsection.csi_section_id) ?? null;
        div.value = section.value
            ? divisionById.get(section.value.csi_division_id) ?? null
            : null;
    }

    search.value = "";
    await nextTick();

    if (div.value) {
        document
            .querySelector(`#division-${CSS.escape(div.value.code)}`)
            ?.scrollIntoView({ block: "nearest", behavior: "smooth" });
    }

    if (section.value) {
        document
            .querySelector(`#section-${CSS.escape(section.value.code)}`)
            ?.scrollIntoView({ block: "nearest", behavior: "smooth" });
    }

    if (subsection.value) {
        document
            .querySelector(`#subsection-${subsection.value.id}`)
            ?.scrollIntoView({ block: "nearest", behavior: "smooth" });
    }
};

const clearSearch = () => {
    search.value = "";
    searchInput.value?.$el?.querySelector("input")?.focus();
};
</script>

<template>
    <Head title="MasterFormat | CSI Reference" />

    <GuestLayout title="MasterFormat Reference" :show-sidebar="true">
        <div
            v-motion-fade
            class="min-h-screen bg-light-tertiary text-slate-800 rounded-md antialiased transition-colors duration-300 dark:bg-dark-tertiary dark:text-slate-100"
        >
            <main class="mx-auto max-w-[1600px] space-y-8 px-4 py-8 sm:px-6 lg:px-10 lg:py-10">
                 <Header :divisions :sections="props.sections" :subsections="props.subsections" />
                <section
                    class="relative z-20 rounded-2xl border border-light-quatrenary bg-light-secondary p-4 shadow-sm sm:p-6 dark:border-dark-quatrenary dark:bg-dark-secondary"
                >
                    <div
                        class="mb-3 flex items-center justify-between gap-3"
                    >
                        <div class="flex items-center gap-2">
                            <Search
                                class="size-4 text-indigo-600 dark:text-indigo-400"
                            />
                            <h2
                                class="text-sm font-semibold text-slate-800 dark:text-slate-100"
                            >
                                Search classifications
                            </h2>
                        </div>

                        <span
                            class="hidden items-center gap-1.5 rounded-md bg-light-tertiary px-2 py-1 text-[11px] font-medium text-slate-500 sm:inline-flex dark:bg-dark-tertiary dark:text-slate-400"
                        >
                            <Command class="size-3" />
                            Quick lookup
                        </span>
                    </div>

                    <div class="relative">
                        <Search
                            class="pointer-events-none absolute left-4 top-1/2 z-10 size-5 -translate-y-1/2 text-slate-400"
                        />

                        <Input
                            ref="searchInput"
                            v-model="search"
                            placeholder="Search by code or description..."
                            autocomplete="off"
                            class="h-12 rounded-xl border border-light-quatrenary bg-light-primary pl-12 pr-12 text-sm shadow-none transition-all placeholder:text-slate-400 focus-visible:border-indigo-400 focus-visible:ring-4 focus-visible:ring-indigo-500/10 dark:border-dark-quatrenary dark:bg-dark-primary dark:text-white dark:placeholder:text-slate-500 dark:focus-visible:border-indigo-400"
                        />

                        <button
                            v-if="search"
                            type="button"
                            aria-label="Clear search"
                            class="absolute right-3 top-1/2 flex size-8 -translate-y-1/2 items-center justify-center rounded-lg text-slate-400 transition hover:bg-light-tertiary hover:text-slate-700 dark:hover:bg-dark-tertiary dark:hover:text-white"
                            @click="clearSearch"
                        >
                            <X class="size-4" />
                        </button>
                    </div>

                    <p
                        class="mt-3 text-xs text-slate-500 dark:text-slate-400"
                    >
                        Search across all divisions, sections, and subsections.
                        Results update as you type.
                    </p>

                    <!-- Search results -->
                    <div
                        v-if="filteredResults.length"
                        v-motion-fade
                        class="absolute inset-x-4 top-[calc(100%-0.5rem)] overflow-hidden rounded-2xl border border-light-quatrenary bg-light-secondary shadow-xl shadow-slate-900/10 sm:inset-x-6 dark:border-dark-quatrenary dark:bg-dark-secondary dark:shadow-black/30"
                    >
                        <div
                            class="flex items-center justify-between border-b border-light-quatrenary px-4 py-3 dark:border-dark-quatrenary"
                        >
                            <span
                                class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400"
                            >
                                Matching classifications
                            </span>
                            <span
                                class="rounded-md bg-indigo-100 px-2 py-0.5 text-xs font-semibold text-indigo-700 dark:bg-indigo-400/15 dark:text-indigo-300"
                            >
                                {{ filteredResults.length }} results
                            </span>
                        </div>

                        <div class="max-h-80 overflow-y-auto p-2">
                            <button
                                v-for="result in filteredResults"
                                :key="`${result.item.type}-${result.item.id}`"
                                type="button"
                                class="group flex w-full items-center gap-3 rounded-xl px-3 py-3 text-left transition-colors hover:bg-light-tertiary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 dark:hover:bg-dark-tertiary"
                                @click.prevent="selectSearch(result)"
                            >
                                <div
                                    class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-light-tertiary text-slate-500 transition-colors group-hover:bg-indigo-100 group-hover:text-indigo-700 dark:bg-dark-tertiary dark:text-slate-400 dark:group-hover:bg-indigo-400/15 dark:group-hover:text-indigo-300"
                                >
                                    <Layers3
                                        v-if="result.item.type === 'division'"
                                        class="size-5"
                                    />
                                    <FolderTree
                                        v-else-if="result.item.type === 'section'"
                                        class="size-5"
                                    />
                                    <FileText v-else class="size-5" />
                                </div>

                                <div class="min-w-0 flex-1">
                                    <div
                                        class="flex flex-wrap items-center gap-2"
                                    >
                                        <span
                                            class="rounded-md bg-light-tertiary px-2 py-0.5 font-mono text-xs font-semibold text-slate-700 dark:bg-dark-tertiary dark:text-slate-200"
                                        >
                                            {{ result.item.fullCode }}
                                        </span>
                                        <span
                                            class="text-[10px] font-semibold uppercase tracking-wider text-slate-400"
                                        >
                                            {{ result.item.type }}
                                        </span>
                                    </div>
                                    <p
                                        class="mt-1 truncate text-sm font-medium text-slate-800 dark:text-slate-100"
                                    >
                                        {{ result.item.name }}
                                    </p>
                                </div>

                                <ChevronRight
                                    class="size-4 shrink-0 text-slate-300 transition group-hover:translate-x-0.5 group-hover:text-indigo-500 dark:text-slate-600"
                                />
                            </button>
                        </div>
                    </div>

                    <div
                        v-else-if="search.trim().length >= 2"
                        class="absolute inset-x-4 top-[calc(100%-0.5rem)] rounded-2xl border border-light-quatrenary bg-light-secondary px-5 py-8 text-center shadow-xl sm:inset-x-6 dark:border-dark-quatrenary dark:bg-dark-secondary"
                    >
                        <Search
                            class="mx-auto size-7 text-slate-300 dark:text-slate-600"
                        />
                        <p
                            class="mt-3 text-sm font-semibold text-slate-700 dark:text-slate-200"
                        >
                            No matching classifications
                        </p>
                        <p
                            class="mt-1 text-xs text-slate-500 dark:text-slate-400"
                        >
                            Try another code or description.
                        </p>
                    </div>
                </section>

                <!-- Current selection -->
                 <Current
                    :div :section :subsection :activePath
                 />

                <!-- Classification hierarchy -->
                <section class="space-y-4">
                    <div
                        class="flex flex-col justify-between gap-2 sm:flex-row sm:items-end"
                    >
                        <div>
                            <h2
                                class="text-lg font-bold tracking-tight text-slate-900 dark:text-white"
                            >
                                Classification hierarchy
                            </h2>
                            <p
                                class="mt-1 text-sm text-slate-500 dark:text-slate-400"
                            >
                                Select a division, then drill down into its
                                sections and subsections.
                            </p>
                        </div>

                        <span
                            class="text-xs font-medium text-slate-400 dark:text-slate-500"
                        >
                            {{ props.divisions.length }} divisions
                            <span class="mx-1.5">·</span>
                            {{ props.sections.length }} sections
                            <span class="mx-1.5">·</span>
                            {{ props.subsections.length }} subsections
                        </span>
                    </div>

                    <div
                        class="grid grid-cols-1 gap-5 lg:grid-cols-3"
                    >
                        <Panel name="Division" :items="props.divisions">
                            <button
                                v-for="division in props.divisions"
                                :key="division.id"
                                :id="`division-${division.code}`"
                                type="button"
                                class="group flex w-full items-start gap-3 rounded-xl border border-transparent px-3 py-3 text-left transition-all hover:bg-light-tertiary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 dark:hover:bg-dark-tertiary"
                                :class="{
                                    'border-indigo-200 bg-indigo-50 shadow-sm dark:border-indigo-400/30 dark:bg-indigo-400/10':
                                        div?.id === division.id,
                                }"
                                @click="selectDivision(division)"
                            >
                                <span
                                    class="mt-0.5 flex size-9 shrink-0 items-center justify-center rounded-lg bg-light-tertiary font-mono text-[11px] font-bold text-slate-600 group-hover:bg-indigo-100 group-hover:text-indigo-700 dark:bg-dark-tertiary dark:text-slate-300 dark:group-hover:bg-indigo-400/15 dark:group-hover:text-indigo-300"
                                    :class="{
                                        'bg-indigo-100 text-indigo-700 dark:bg-indigo-400/20 dark:text-indigo-300':
                                            div?.id === division.id,
                                    }"
                                >
                                    {{ division.code }}
                                </span>

                                <span class="min-w-0 flex-1">
                                    <span
                                        class="block text-sm font-medium leading-5 text-slate-700 group-hover:text-slate-950 dark:text-slate-200 dark:group-hover:text-white"
                                        :class="{
                                            'font-semibold text-indigo-950 dark:text-indigo-200':
                                                div?.id === division.id,
                                        }"
                                    >
                                        {{ division.name }}
                                    </span>
                                </span>

                                <ChevronRight
                                    class="mt-1 size-4 shrink-0 text-slate-300 transition group-hover:text-indigo-500 dark:text-slate-600"
                                    :class="{
                                        'text-indigo-500 dark:text-indigo-300':
                                            div?.id === division.id,
                                    }"
                                />
                            </button>
                        </Panel>
                        
                        <Panel :key="`section-panel-${div?.id}`" name="Section" :items="sections" :div>
                            <button
                                v-for="refSection in sections"
                                :key="refSection.id"
                                :id="`section-${refSection.code}`"
                                type="button"
                                class="group flex w-full items-start gap-3 rounded-xl border border-transparent px-3 py-3 text-left transition-all hover:bg-light-tertiary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-sky-500 dark:hover:bg-dark-tertiary"
                                :class="{
                                    'border-sky-200 bg-sky-50 shadow-sm dark:border-sky-400/30 dark:bg-sky-400/10':
                                        section?.id === refSection.id,
                                }"
                                @click="selectSection(refSection)"
                            >
                                <span
                                    class="mt-0.5 flex size-9 shrink-0 items-center justify-center rounded-lg bg-light-tertiary font-mono text-[11px] font-bold text-slate-600 group-hover:bg-sky-100 group-hover:text-sky-700 dark:bg-dark-tertiary dark:text-slate-300 dark:group-hover:bg-sky-400/15 dark:group-hover:text-sky-300"
                                    :class="{
                                        'bg-sky-100 text-sky-700 dark:bg-sky-400/20 dark:text-sky-300':
                                            section?.id === refSection.id,
                                    }"
                                >
                                    {{ refSection.code }}
                                </span>

                                <span class="min-w-0 flex-1">
                                    <span
                                        class="block text-sm font-medium leading-5 text-slate-700 group-hover:text-slate-950 dark:text-slate-200 dark:group-hover:text-white"
                                        :class="{
                                            'font-semibold text-sky-950 dark:text-sky-200':
                                                section?.id === refSection.id,
                                        }"
                                    >
                                        {{ refSection.name }}
                                    </span>
                                    <span
                                        class="mt-1 block font-mono text-[10px] text-slate-400 dark:text-slate-500"
                                    >
                                        {{ div?.code }}
                                        {{ refSection.code }}
                                    </span>
                                </span>

                                <ChevronRight
                                    class="mt-1 size-4 shrink-0 text-slate-300 transition group-hover:text-sky-500 dark:text-slate-600"
                                    :class="{
                                        'text-sky-500 dark:text-sky-300':
                                            section?.id === refSection.id,
                                    }"
                                />
                            </button>
                        </Panel>
                        
                        <Panel :key="`subsection-panel-${section?.id}`" name="Subsection" :items="subsections" :section>
                            <button
                                v-for="refSubsection in subsections"
                                :key="refSubsection.id"
                                :id="`subsection-${refSubsection.id}`"
                                type="button"
                                class="group flex w-full items-start gap-3 rounded-xl border border-transparent px-3 py-3 text-left transition-all hover:bg-light-tertiary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 dark:hover:bg-dark-tertiary"
                                :class="{
                                    'border-emerald-200 bg-emerald-50 shadow-sm dark:border-emerald-400/30 dark:bg-emerald-400/10':
                                        subsection?.id === refSubsection.id,
                                }"
                                @click="subsection = refSubsection"
                            >
                                <span
                                    class="mt-0.5 flex size-9 shrink-0 items-center justify-center rounded-lg bg-light-tertiary font-mono text-[11px] font-bold text-slate-600 group-hover:bg-emerald-100 group-hover:text-emerald-700 dark:bg-dark-tertiary dark:text-slate-300 dark:group-hover:bg-emerald-400/15 dark:group-hover:text-emerald-300"
                                    :class="{
                                        'bg-emerald-100 text-emerald-700 dark:bg-emerald-400/20 dark:text-emerald-300':
                                            subsection?.id === refSubsection.id,
                                    }"
                                >
                                    {{ refSubsection.code }}
                                </span>

                                <span class="min-w-0 flex-1">
                                    <span
                                        class="block text-sm font-medium leading-5 text-slate-700 group-hover:text-slate-950 dark:text-slate-200 dark:group-hover:text-white"
                                        :class="{
                                            'font-semibold text-emerald-950 dark:text-emerald-200':
                                                subsection?.id === refSubsection.id,
                                        }"
                                    >
                                        {{ refSubsection.name }}
                                    </span>
                                    <span
                                        class="mt-1 block font-mono text-[10px] text-slate-400 dark:text-slate-500"
                                    >
                                        {{ div?.code }}
                                        {{ section?.code }}
                                        {{ refSubsection.code }}
                                    </span>
                                </span>
                            </button>
                        </Panel>
                    </div>
                </section>
            </main>
        </div>
    </GuestLayout>
</template>