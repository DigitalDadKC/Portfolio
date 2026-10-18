import { ref, onMounted, onUnmounted } from 'vue'

const scrolled = ref(false)
let users = 0

const update = () => {
    scrolled.value = window.scrollY > 50
}

export function useScrolled() {
    onMounted(() => {
        if (users++ === 0) window.addEventListener('scroll', update, { passive: true })
        update()
    })

    onUnmounted(() => {
        if (--users === 0) window.removeEventListener('scroll', update)
    })

    return { scrolled }
}