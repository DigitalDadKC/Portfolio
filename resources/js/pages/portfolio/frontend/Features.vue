```vue
<script setup>
import { ref } from 'vue'
import { useSortable } from '@vueuse/integrations/useSortable'

const props = defineProps({
    features: Object,
})

const isDragging = ref(false)
const featuresList = ref([...props.features])
const featuresContainer = ref(null)

useSortable(featuresContainer, featuresList, {
    animation: 180,
    ghostClass: 'feature-ghost',
    chosenClass: 'feature-chosen',
    dragClass: 'feature-dragging',

    onStart() {
        isDragging.value = true
    },

    onEnd() {
        isDragging.value = false
    },
})
</script>

<template>
    <section
        id="features"
        class="
            relative overflow-hidden
            section
            bg-light-tertiary dark:bg-dark-quatrenary
            py-24
        "
    >
        <!-- Decorative background shapes -->
        <div
            class="
                pointer-events-none absolute -right-20 -top-20
                h-64 w-64 rounded-full
                border-2 border-black/10
                dark:border-white/10
            "
        />

        <div
            class="
                pointer-events-none absolute -bottom-32 -left-20
                h-72 w-72 rounded-full
                bg-light-quatrenary/40
                dark:bg-dark-tertiary/20
            "
        />

        <div class="relative mx-auto max-w-6xl px-6">

            <!-- Heading -->
            <div class="mb-12 text-center">
                <span
                    class="
                        mb-3 inline-block
                        rounded-full border-2 border-black
                        bg-light-quatrenary
                        px-4 py-1
                        text-sm font-bold uppercase tracking-widest
                        shadow-[3px_3px_0px_0px_rgba(0,0,0,1)]
                    "
                >
                    What I use
                </span>

                <h2
                    class="
                        section-title
                        dark:text-dark-secondary
                    "
                >
                    Features
                </h2>

                <p
                    class="
                        mx-auto mt-4 max-w-xl
                        text-gray-600 dark:text-gray-300
                    "
                >
                    A collection of tools, technologies, and features
                    that give a wide range of capabilities and functionality
                    that bring my projects to life
                </p>

                <!-- Drag hint -->
                <div
                    class="
                        mt-6 inline-flex items-center gap-2
                        text-sm font-medium
                        text-gray-500 dark:text-gray-400
                    "
                >
                    <div
                        class="
                            mt-6 inline-flex items-center gap-2
                            text-sm font-medium
                            text-gray-500 dark:text-gray-400
                        "
                    >
                        <span class="text-base">✦</span>
                        Drag any card to rearrange
                    </div>
                </div>
            </div>

            <!-- Features -->
            <div
                ref="featuresContainer"
                :class="[
                    'grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4',
                    { 'is-dragging': isDragging }
                ]"
            >
                <div
                    v-for="(feature, index) in featuresList"
                    :key="feature.id ?? index"
                    class="
                        feature-card
                        group
                        relative
                        flex items-center gap-4
                        rounded-xl
                        border-2 border-black
                        bg-white/70 dark:bg-black/20
                        px-5 py-4
                        font-semibold
                        text-accent
                        shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]
                        cursor-grab
                        active:cursor-grabbing
                        select-none
                        transition-[opacity,filter,box-shadow,background-color]
                        duration-200
                        ease-out
                    "
                >
                    <!-- Icon -->
                    <div
                        class="
                            flex h-10 w-10 shrink-0
                            items-center justify-center
                            rounded-lg
                            border-2 border-black
                            bg-light-quatrenary
                            transition-transform duration-200 ease-out
                            group-hover:rotate-[-6deg]
                            group-hover:scale-105
                        "
                    >
                        <svg
                            class="h-5 w-5"
                            aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke="currentColor"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2.5"
                                d="m8.5 11.5 2.5 2.5 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                            />
                        </svg>
                    </div>

                    <!-- Text -->
                    <p class="truncate text-base lg:text-lg">
                        {{ feature.name }}
                    </p>
                </div>
            </div>

        </div>
    </section>
</template>

<style scoped>
/*
 * The card currently being dragged.
 */
:deep(.feature-dragging) {
    z-index: 50;
    transform: rotate(-1.5deg) scale(1.04);
    opacity: 0.95;

    box-shadow:
        8px 8px 0 rgba(0, 0, 0, 0.9);
}

/*
 * Placeholder where the dragged card came from.
 */
:deep(.feature-ghost) {
    opacity: 0.3;

    background: transparent;

    border-style: dashed;

    box-shadow: none;
}

/*
 * The card that was selected before dragging.
 */
:deep(.feature-chosen) {
    cursor: grabbing;
}

/*
 * While dragging, subtly de-emphasize
 * the cards that aren't being dragged.
 */
:deep(.is-dragging .feature-card:not(.feature-dragging):not(.feature-ghost)) {
    opacity: 0.65;
    filter: saturate(0.75);
    transform: scale(0.97);
}

/*
 * Make the surrounding cards feel like they're
 * making room for the dragged card.
 */
:deep(.is-dragging .feature-card:not(.feature-dragging):hover) {
    transform: scale(0.98);
}

/*
 * Restore normal appearance after dragging.
 */
:deep(.feature-card) {
    transition:
        opacity 200ms ease,
        filter 200ms ease,
        box-shadow 200ms ease,
        background-color 200ms ease;
}
</style>