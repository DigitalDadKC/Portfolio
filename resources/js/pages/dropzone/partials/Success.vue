<script setup lang="ts">
import { CheckCircle2, X, FileSpreadsheet } from "lucide-vue-next";
import PivotTable from "./PivotTable.vue"; // adjust the path to where you place it

defineProps<{
    open: boolean;
    title?: string;
    message?: string;
    items: Record<string, unknown>[];
}>()

const emit = defineEmits<{
    (e: "close"): void;
    (e: "continue"): void;
}>();
</script>

<template>
    <Teleport to="body">
        <div
            v-if="open"
            class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 p-4 backdrop-blur-sm"
            @click.self="emit('close')"
        >
            <div
                class="relative max-h-[92vh] w-full max-w-6xl overflow-y-auto rounded-2xl border bg-background p-8 text-center shadow-2xl"
                role="dialog"
                aria-modal="true"
                aria-labelledby="success-title"
            >
                <button
                    type="button"
                    class="absolute right-4 top-4 rounded-md p-2 text-muted-foreground hover:bg-muted"
                    @click="emit('close')"
                    aria-label="Close"
                >
                    <X class="size-4" />
                </button>

                <div
                    class="mx-auto mb-5 flex size-16 items-center justify-center rounded-full bg-emerald-500/10"
                >
                    <CheckCircle2 class="size-9 text-emerald-600" />
                </div>

                <h2
                    id="success-title"
                    class="text-2xl font-bold tracking-tight"
                >
                    {{ title }}
                </h2>

                <p class="mt-3 text-sm leading-6 text-muted-foreground">
                    {{ message }}
                </p>

                <div
                    class="mt-6 flex items-center justify-center gap-2 rounded-lg border bg-muted/30 p-3 text-sm"
                >
                    <FileSpreadsheet class="size-5 text-emerald-600" />
                    Spreadsheet processed successfully
                </div>

                <div class="mt-6">
                    <PivotTable v-if="items?.length" :rows="items" />
                    <p v-else class="text-sm text-muted-foreground">
                        No rows to analyze.
                    </p>
                </div>
            </div>
        </div>
    </Teleport>
</template>