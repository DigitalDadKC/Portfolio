<script setup lang="ts">
import { ref, computed } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import GuestLayout from "@/layouts/GuestLayout.vue";
import ProjectHeader from "@/components/ProjectHeader.vue";
import { Button } from "@/components/ui/button";
import { Progress } from "@/components/ui/progress";
import { Upload, FileSpreadsheet, UploadCloud, X, FileCheck2, CircleAlert } from "lucide-vue-next";
import Success from "./partials/Success.vue";

interface SpreadsheetRow {
    [key: string]: any;
}

interface PageProps {
    flash: {
        imported: SpreadsheetRow[] | null;
    };
}

const emit = defineEmits<{
    (e: "select", file: File): void;
    (e: "remove"): void;
}>();

const page = usePage<PageProps>();
const imported = computed(() => page.props.flash.imported ?? [])
const fileInput = ref<HTMLInputElement | null>(null);
const selectedFile = ref<File | null>(null);
const isDragging = ref(false);
const isUploading = ref(false);
const error = ref("");
const successModal = ref(false);
const progress = ref(0);

const MAX_SIZE = 10 * 1024 * 1024;
const ACCEPTED_EXTENSIONS = ["xlsx", "xls", "csv"];

const formattedSize = computed(() => {
    if (!selectedFile.value) return "";

    const size = selectedFile.value.size;

    return size < 1024 * 1024
        ? `${(size / 1024).toFixed(1)} KB`
        : `${(size / (1024 * 1024)).toFixed(2)} MB`;
});

function openFilePicker() {
    if (!isUploading.value) fileInput.value?.click();
}

function handleFile(file?: File) {
    if (!file) return;

    error.value = "";

    const extension = file.name.split(".").pop()?.toLowerCase();

    if (!extension || !ACCEPTED_EXTENSIONS.includes(extension)) {
        error.value = "Please select an Excel (.xlsx, .xls) or CSV file.";
        return;
    }

    if (file.size === 0) {
        error.value = "This file is empty. Please select another file.";
        return;
    }

    if (file.size > MAX_SIZE) {
        error.value = "File size must be 10 MB or less.";
        return;
    }

    const formData = new FormData()
    formData.append('file', file)
    progress.value = 0;
    isUploading.value = true;

    router.post(route('dropzone.import'), formData, {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            successModal.value = true;
            isUploading.value = false
        },
        onFinish: () => {
            isUploading.value = false;
        },
    })
}

function handleInput(event: Event) {
    const input = event.target as HTMLInputElement;
    handleFile(input.files?.[0]);
    input.value = "";
}

function handleDrop(event: DragEvent) {
    isDragging.value = false;
    handleFile(event.dataTransfer?.files?.[0]);
}

function removeFile() {
    selectedFile.value = null;
    error.value = "";
    progress.value = 0;
    isUploading.value = false;
}
</script>

<template>
    <GuestLayout :show-sidebar="true">

        <main
            class="mx-auto max-w-[1600px] space-y-8 px-4 py-8 sm:px-6 lg:px-10 lg:py-10"
        >

        {{ successModal }}
            <div class="mx-auto max-w-7xl space-y-8">
                <ProjectHeader
                    title="Dropzone"
                    subtitle="Spreadsheet Import"
                    explorer
                >
                    Upload your spreadsheets for a quick analysis
                    <template #icon>
                        <Upload class="size-5" />
                    </template>
                </ProjectHeader>

                <div class="flex justify-center">
                    <div class="space-y-4">
                        <input
                            ref="fileInput"
                            type="file"
                            accept=".xlsx,.xls,.csv"
                            class="sr-only"
                            @change="handleInput"
                        />

                        <div
                            role="button"
                            tabindex="0"
                            :aria-label="
                                selectedFile
                                    ? 'Selected file details'
                                    : 'Upload an Excel file'
                            "
                            :aria-disabled="isUploading"
                            @click="openFilePicker"
                            @keydown.enter.prevent="openFilePicker"
                            @keydown.space.prevent="openFilePicker"
                            @dragenter.prevent="isDragging = true"
                            @dragover.prevent="isDragging = true"
                            @dragleave.prevent="isDragging = false"
                            @drop.prevent="handleDrop"
                            :class="[
                                'relative flex min-h-64 cursor-pointer flex-col items-center justify-center',
                                'rounded-xl border-2 border-dashed p-8 text-center transition-all duration-200',
                                'focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring',
                                isDragging
                                    ? 'border-primary bg-primary/5 scale-[1.01]'
                                    : 'border-muted-foreground/25 bg-muted/20 hover:border-primary/50 hover:bg-muted/40',
                                isUploading && 'cursor-not-allowed opacity-70',
                            ]"
                        >
                            <template v-if="!selectedFile">
                                <div
                                    class="mb-5 flex size-14 items-center justify-center rounded-2xl border bg-background shadow-sm"
                                >
                                    <UploadCloud
                                        class="size-7 text-primary"
                                        :class="{ 'animate-bounce': isDragging }"
                                    />
                                </div>

                                <h3 class="text-lg font-semibold tracking-tight">
                                    {{
                                        isDragging
                                            ? "Drop your file here"
                                            : "Upload your spreadsheet"
                                    }}
                                </h3>

                                <p
                                    class="mt-2 max-w-sm text-sm leading-6 text-muted-foreground"
                                >
                                    Drag and drop your Excel file here, or
                                    <span
                                        class="font-medium text-primary underline underline-offset-4"
                                    >
                                        browse files
                                    </span>
                                </p>

                                <div
                                    class="mt-5 flex flex-wrap items-center justify-center gap-2"
                                >
                                    <span
                                        v-for="type in ['XLSX', 'XLS', 'CSV']"
                                        :key="type"
                                        class="rounded-md border bg-background px-2 py-1 text-xs font-medium text-muted-foreground"
                                    >
                                        {{ type }}
                                    </span>

                                    <span class="text-xs text-muted-foreground">
                                        Up to 10 MB
                                    </span>
                                </div>
                            </template>

                            <template v-else>
                                <div
                                    class="mb-4 flex size-14 items-center justify-center rounded-2xl bg-emerald-500/10"
                                >
                                    <FileSpreadsheet class="size-7 text-emerald-600" />
                                </div>

                                <h3 class="max-w-full break-all text-base font-semibold">
                                    {{ selectedFile.name }}
                                </h3>

                                <p class="mt-1 text-sm text-muted-foreground">
                                    {{ formattedSize }}
                                </p>

                                <div
                                    class="mt-4 flex items-center gap-2 text-sm font-medium text-emerald-600"
                                >
                                    <FileCheck2 class="size-4" />
                                    Ready to analyze
                                </div>

                                <Button
                                    variant="ghost"
                                    size="sm"
                                    class="mt-3"
                                    :disabled="isUploading"
                                    @click.stop="removeFile"
                                >
                                    <X class="mr-2 size-4" />
                                    Remove file
                                </Button>
                            </template>
                        </div>

                        <div
                            v-if="error"
                            role="alert"
                            class="flex items-start gap-2 rounded-lg border border-destructive/20 bg-destructive/5 p-3 text-sm text-destructive"
                        >
                            <CircleAlert class="mt-0.5 size-4 shrink-0" />
                            {{ error }}
                        </div>

                        <div v-if="isUploading" class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-muted-foreground"
                                    >Uploading spreadsheet...</span
                                >
                                <span class="font-medium tabular-nums"
                                    >{{ progress }}%</span
                                >
                            </div>

                            <Progress :model-value="progress" />
                        </div>

                        <p class="text-center text-xs leading-5 text-muted-foreground">
                            Your spreadsheet is processed securely. Please don't upload
                            sensitive or confidential information.
                        </p>
                    </div>
                </div>
            </div>
        </main>

        <Success
            :open="successModal"
            title="Import Successful!"
            :message="`${imported.length} rows have been uploaded successfully. You're ready to start analyzing your data.`"
            :items="imported"
            @close="successModal = false"
            @continue="successModal = false"
        />
    </GuestLayout>
</template>