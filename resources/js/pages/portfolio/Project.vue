<script setup lang="ts">
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card'

defineProps<{
    project: {
        name: string
        description: string
        image: string
        url: string
    }
}>()
</script>

<template>
    <a
        :href="project.url"
        target="_blank"
        rel="noopener noreferrer"
        class="group block h-full"
        v-motion
        :initial="{ opacity: 0, y: 40 }"
        :visibleOnce="{ opacity: 1, y: 0 }"
    >
        <Card
            class="
                relative h-full overflow-hidden
                bg-light-tertiary
                border-2 border-black
                rounded-2xl
                shadow-[6px_6px_0px_0px_rgba(0,0,0,1)]
                transition-all duration-300 ease-out

                group-hover:-translate-y-1
                group-hover:translate-x-[-2px]
                group-hover:shadow-[10px_10px_0px_0px_rgba(0,0,0,1)]
            "
        >
            <!-- Header -->
            <CardHeader class="p-5 pb-4">
                <!-- Title -->
                <div class="flex min-h-[4rem] items-start justify-between gap-4">
                    <CardTitle
                        class="
                            min-w-0
                            text-xl font-bold leading-tight tracking-tight
                            line-clamp-2
                            transition-colors duration-200
                            group-hover:text-primary
                        "
                    >
                        {{ project.name }}
                    </CardTitle>

                    <span
                        class="
                            shrink-0 flex h-8 w-8 items-center justify-center
                            rounded-full border-2 border-black
                            bg-light-quatrenary
                            text-sm font-bold
                            transition-transform duration-300
                            group-hover:rotate-[-8deg]
                            group-hover:scale-110
                        "
                    >
                        ↗
                    </span>
                </div>

                <!-- Subtitle -->
                <div class="min-h-[2.625rem] mt-3">
                    <CardDescription
                        v-if="project.description"
                        class="
                            text-sm leading-relaxed text-gray-600
                            line-clamp-2
                        "
                    >
                        {{ project.description }}
                    </CardDescription>
                </div>
            </CardHeader>

            <!-- Image -->
            <CardContent class="p-3 pt-0">
                <div
                    class="
                        relative overflow-hidden
                        rounded-xl
                        border-2 border-black
                        bg-black
                    "
                >
                    <img
                        :src="project.image"
                        :alt="project.name"
                        class="
                            block h-44 w-full
                            object-cover
                            transition-transform duration-500 ease-out
                            lg:h-56
                            group-hover:scale-105
                        "
                    />

                    <!-- Image overlay -->
                    <div
                        class="
                            pointer-events-none absolute inset-0
                            bg-gradient-to-t
                            from-black/20
                            via-transparent
                            to-transparent
                            opacity-60
                        "
                    />
                </div>
            </CardContent>
        </Card>
    </a>
</template>