<script setup lang="ts">
import { ChevronDown } from 'lucide-vue-next'
import {
    Accordion,
    AccordionContent,
    AccordionItem,
    AccordionTrigger,
} from '@/components/ui/accordion'

interface Question {
    question: string
    answer: string
}

const props = withDefaults(
    defineProps<{
        questions?: Question[]
    }>(),
    {
        questions: () => [
            {
                question: 'What kind of construction software do you build?',
                answer:
                    'Job costing and estimating tools, scheduling and crew dispatch dashboards, punch list and inspection apps, client portals, and internal ops software that replaces spreadsheets and whiteboards on the job site.',
            },
            {
                question: 'Why Laravel for construction companies specifically?',
                answer:
                    'Laravel lets me ship reliable, secure software fast, which matters when you need something your foremen and office staff will actually use. It also scales cleanly as your company adds crews, projects, and reporting needs.',
            },
            {
                question: 'Do you work with clients outside your local area?',
                answer:
                    'Yes. I work with contractors and construction companies both locally and fully remote, with regular video check-ins and async updates so you always know where the project stands.',
            },
            {
                question: 'Can you integrate with tools we already use?',
                answer:
                    'In most cases, yes. I\'ve connected Laravel apps to Google Maps for integrated address search + map widget, SAM.gov for government contracting, and other estimating or accounting tools, and I can build custom integrations for whatever your office already runs on.',
            },
            {
                question: 'How long does a typical build take?',
                answer:
                    'A focused internal tool usually takes 3 to 6 weeks. Larger platforms with scheduling, invoicing, or multi-role permissions can run 2 to 4 months. You\'ll get a clear timeline before any work starts.',
            },
            {
                question: 'What happens after launch?',
                answer:
                    'I stick around. Whether it\'s a monthly support retainer, ongoing feature work as your business grows, or just being on call when something breaks, I don\'t disappear once the software ships.',
            },
            {
                question: 'Why hire you instead of just buying a SaaS product off the shelf?',
                answer:
                    'Off-the-shelf tools forces you adapt your workflow to their software. I build around how your crews and office already operate, so you\'re not paying for features you don\'t need or working around ones you do.',
            },
        ],
    }
)
</script>

<template>
    <section
        id="questions"
        class="
            relative overflow-hidden
            section
            bg-light-primary
            dark:bg-dark-primary
            py-24
        "
    >
        <!-- Decorative background shapes -->
        <div
            class="
                pointer-events-none absolute -left-20 -top-24
                h-64 w-64 rounded-full
                border-2 border-black/10
                dark:border-white/10
            "
        />

        <div
            class="
                pointer-events-none absolute -bottom-28 -right-24
                h-72 w-72 rounded-full
                bg-light-quatrenary/40
                dark:bg-dark-tertiary/20
            "
        />

        <div class="relative mx-auto max-w-3xl px-6">

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
                    Got questions?
                </span>

                <h2
                    class="
                        section-title
                        dark:text-gray-400
                    "
                >
                    Frequently asked
                    <span class="text-accent">questions</span>
                </h2>

                <p
                    class="
                        mx-auto mt-4 max-w-xl
                        text-gray-600 dark:text-gray-800
                    "
                >
                    A few things people usually want to know before
                    reaching out. Can't find your answer? Just ask.
                </p>
            </div>

            <!-- Accordion -->
            <Accordion
                type="single"
                collapsible
                class="flex flex-col gap-4"
            >
                <AccordionItem
                    v-for="(item, index) in questions"
                    :key="index"
                    :value="`item-${index}`"
                    class="
                        group
                        overflow-hidden
                        rounded-xl
                        border-2 border-black
                        bg-white/70 dark:bg-black/20
                        shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]
                        transition-[box-shadow,background-color]
                        duration-200
                        ease-out
                    "
                >
                    <AccordionTrigger
                        class="
                            flex w-full items-center justify-between
                            gap-4
                            px-5 py-4
                            text-left
                            font-semibold
                            text-accent
                            text-base lg:text-lg
                            cursor-pointer
                        "
                    >
                        {{ item.question }}
                    </AccordionTrigger>

                    <AccordionContent
                        class="
                            overflow-hidden
                            px-5
                            text-sm lg:text-base
                            leading-6
                            text-gray-600 dark:text-gray-300
                            data-[state=closed]:animate-accordion-up
                            data-[state=open]:animate-accordion-down
                        "
                    >
                        <p class="pb-4 pt-0">
                            {{ item.answer }}
                        </p>
                    </AccordionContent>
                </AccordionItem>
            </Accordion>

        </div>
    </section>
</template>