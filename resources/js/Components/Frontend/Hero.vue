<script setup>

</script>

<template>
    <section id="home" class="md:h-[65vh] lg:h-[85vh] flex items-center bg-light-primary dark:bg-dark-primary pt-32 lg:py-0 overflow-hidden">
        <div class="container mx-auto" v-motion-slide-right>
            <div class="flex flex-col md:flex-row items-center">
                <div class="lg:flex flex-none md:flex-1 items-center mt-8 md:mt-0 md:mr-10 lg:mr-20 max-w-xl">
                    <img src="img/profile.jpg" alt="Hero" class="rounded-lg" /><br>
                </div>
                <div class="flex flex-col items-center lg:items-end text-right">
                    <p class="text-lg text-accent mb-[2px] mt-5 lg:mt-0">Hey I'm Raleigh! </p><blockquote class="text-center md:text-right">(Raw-lee)</blockquote>
                    <h1 class="text-4xl leading-[44px] md:text-5xl md:leading-tight lg:text-7xl text-center lg:text-right lg:leading-[1.2] font-bold md:tracking-[-2px]">
                        I make web apps.
                    </h1>
                    <p class="pt-4 md:pt-6 md:pb-12 max-w-[480px] text-lg">Let me transform your two-dimensional spreadsheets into a modern, sophisticated app just for you and your team.</p>
                    <div class="py-12">
                        <h5 class="text-lg flex items-center justify-center lg:justify-end text-black dark:text-white mb-2">
                            Connect with me!
                        </h5>
                        <div class="flex items-center justify-between sm:justify-start mb-2">
                            <a target="_blank" class="w-12 h-12 rounded-full flex items-center justify-center text-black dark:text-white hover:bg-light-secondary hover:border-light-secondary dark:hover:bg-dark-secondary dark:hover:border-dark-secondary mr-3 sm:mr-4" href="mailto:raleighgroesbeck@gmail.com" aria-label="email link">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 9v.906a2.25 2.25 0 01-1.183 1.981l-6.478 3.488M2.25 9v.906a2.25 2.25 0 001.183 1.981l6.478 3.488m8.839 2.51l-4.66-2.51m0 0l-1.023-.55a2.25 2.25 0 00-2.134 0l-1.022.55m0 0l-4.661 2.51m16.5 1.615a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V8.844a2.25 2.25 0 011.183-1.98l7.5-4.04a2.25 2.25 0 012.134 0l7.5 4.04a2.25 2.25 0 011.183 1.98V19.5z" /></svg>
                            </a>
                            <a target="_blank" class="w-12 h-12 rounded-full flex items-center justify-center text-black dark:text-white hover:bg-light-secondary hover:border-light-secondary dark:hover:bg-dark-secondary dark:hover:border-dark-secondary mr-3 sm:mr-4" href="https://www.linkedin.com/in/digitaldadkc/" aria-label="linkedin link">
                                <svg viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <rect x="2" y="2" width="28" height="28" rx="14" fill="#1275B1"></rect> <path d="M12.6186 9.69215C12.6186 10.6267 11.8085 11.3843 10.8093 11.3843C9.81004 11.3843 9 10.6267 9 9.69215C9 8.7576 9.81004 8 10.8093 8C11.8085 8 12.6186 8.7576 12.6186 9.69215Z" fill="white"></path> <path d="M9.24742 12.6281H12.3402V22H9.24742V12.6281Z" fill="white"></path> <path d="M17.3196 12.6281H14.2268V22H17.3196C17.3196 22 17.3196 19.0496 17.3196 17.2049C17.3196 16.0976 17.6977 14.9855 19.2062 14.9855C20.911 14.9855 20.9008 16.4345 20.8928 17.5571C20.8824 19.0244 20.9072 20.5219 20.9072 22H24V17.0537C23.9738 13.8954 23.1508 12.4401 20.4433 12.4401C18.8354 12.4401 17.8387 13.1701 17.3196 13.8305V12.6281Z" fill="white"></path> </g></svg>
                            </a>
                            <a target="_blank" class="w-12 h-12 rounded-full flex items-center justify-center border border-black text-black dark:text-white hover:bg-light-secondary hover:border-light-secondary dark:hover:bg-dark-secondary dark:hover:border-dark-secondary mr-3 sm:mr-4" href="https://github.com/DigitalDadKC" aria-label="github link">
                                <svg viewBox="0 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>github [#142]</title> <desc>Created with Sketch.</desc> <defs> </defs> <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="Dribbble-Light-Preview" transform="translate(-140.000000, -7559.000000)" fill="#000000"> <g id="icons" transform="translate(56.000000, 160.000000)"> <path d="M94,7399 C99.523,7399 104,7403.59 104,7409.253 C104,7413.782 101.138,7417.624 97.167,7418.981 C96.66,7419.082 96.48,7418.762 96.48,7418.489 C96.48,7418.151 96.492,7417.047 96.492,7415.675 C96.492,7414.719 96.172,7414.095 95.813,7413.777 C98.04,7413.523 100.38,7412.656 100.38,7408.718 C100.38,7407.598 99.992,7406.684 99.35,7405.966 C99.454,7405.707 99.797,7404.664 99.252,7403.252 C99.252,7403.252 98.414,7402.977 96.505,7404.303 C95.706,7404.076 94.85,7403.962 94,7403.958 C93.15,7403.962 92.295,7404.076 91.497,7404.303 C89.586,7402.977 88.746,7403.252 88.746,7403.252 C88.203,7404.664 88.546,7405.707 88.649,7405.966 C88.01,7406.684 87.619,7407.598 87.619,7408.718 C87.619,7412.646 89.954,7413.526 92.175,7413.785 C91.889,7414.041 91.63,7414.493 91.54,7415.156 C90.97,7415.418 89.522,7415.871 88.63,7414.304 C88.63,7414.304 88.101,7413.319 87.097,7413.247 C87.097,7413.247 86.122,7413.234 87.029,7413.87 C87.029,7413.87 87.684,7414.185 88.139,7415.37 C88.139,7415.37 88.726,7417.2 91.508,7416.58 C91.513,7417.437 91.522,7418.245 91.522,7418.489 C91.522,7418.76 91.338,7419.077 90.839,7418.982 C86.865,7417.627 84,7413.783 84,7409.253 C84,7403.59 88.478,7399 94,7399" id="github-[#142]"> </path> </g> </g> </g> </g></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
