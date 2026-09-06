<script setup>
import Service from './Service.vue'
import { CodeXml, Download, Earth, FileSpreadsheet, Gauge, Lock } from 'lucide-vue-next';

const service_items = [
    {
        title: 'Development',
        icon: CodeXml,
        detail: 'Tailored solutions are programmed to specifically address the business need, from unique workflows, features, to integrations of varying complexity. Instead of relying on one-size-fits-all solutions, the app is built to match exactly how you operate.'
    }, {
        title: 'Ecosystem',
        icon: Earth,
        detail: 'Operate safely in a secure, self-contained environment where all user interactions, data, and features operate within the app—without relying on external platforms. This gives the business full control over user access, data privacy, and the overall experience.'
    }, {
        title: 'Dashboards',
        icon: Gauge,
        detail: 'Dashboards visually present key data and metrics in one central location, using tools such as charts, tables, and summaries. These dashboards can be customized for different users or roles, helping them monitor performance and make informed decisions at a glance.'
    }, {
        title: 'Security',
        icon: Lock,
        detail: 'Built-in features such as encryption, authentication, and protection against common threats such as SQL injection, cross-site scripting (XSS), and cross-site request forgery (CSRF) are provided. It ensures that user data and system access are carefully controlled, so only authorized users can see or do specific things within the app.'
    }, {
        title: 'Datatables',
        icon: FileSpreadsheet,
        detail: 'House your data in an organized, interactive format—similar to a dynamic spreadsheet. Users can quickly search, sort, and filter information in real time, making it easy to find and work with the data they need.'
    }, {
        title: 'Exports',
        icon: Download,
        detail: 'Multiple export options—such as PDF, Excel, or CSV, enable users to easily download and share data in the format that best suits their needs. These exports are generated directly from the app, ensuring accuracy, consistency, and convenience.'
    }
]

</script>

<template>

    <section id="services" class="section bg-light-secondary dark:bg-dark-quatrenary">
        <div class="py-8 px-4 mx-auto max-w-screen-xl sm:py-16 lg:px-6">
            <div class="mb-12 text-center">
                <span
                    class="
                        mb-4 inline-block
                        rounded-full
                        border-2 border-black
                        bg-light-quatrenary
                        px-4 py-1
                        text-sm font-bold
                        uppercase tracking-widest
                        shadow-[3px_3px_0px_0px_rgba(0,0,0,1)]
                    "
                >
                    What I build
                </span>

                <h2
                    class="
                        text-4xl
                        font-extrabold
                        tracking-tight
                        text-black
                        sm:text-5xl
                    "
                >
                    Services that produce
                    <span class="text-accent">WOW.</span>
                </h2>

                <p
                    class="
                        mx-auto mt-4
                        max-w-2xl
                        text-gray-600
                        dark:text-gray-300
                    "
                >
                    Purpose-built tools that turn complicated business processes
                    into simple, useful software.
                </p>
            </div>
            <div
                class="
                    grid
                    gap-6
                    md:grid-cols-2
                    lg:grid-cols-3
                "
            >
                <Service
                    v-for="(service, index) in service_items"
                    :key="service.title"
                    :service="service"
                    :delay="index * 80"
                />
            </div>
        </div>
    </section>
</template>

<style>
    .service-enter-active {
        transition:
            opacity 500ms ease,
            transform 500ms cubic-bezier(.2, .8, .2, 1);
        transition-delay: var(--delay);
    }

    .service-enter-from {
        opacity: 0;
        transform: translateY(20px);
    }
</style>