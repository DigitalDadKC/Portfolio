<script setup lang="ts">
import {
  Sidebar,
  SidebarContent,
  SidebarGroup,
  SidebarGroupContent,
  SidebarMenu,
  SidebarMenuButton,
  SidebarMenuItem,
} from "@/components/ui/sidebar"
import SidebarHeader from "./ui/sidebar/SidebarHeader.vue";
import * as icons from 'lucide-vue-next';


const getIcon = (iconName) => {
    return icons[iconName] || icons.Folder;
}
const props = defineProps({
    projects: Object,
})

</script>

<template>
    <Sidebar>
        <SidebarHeader class="pt-20">PROJECTS</SidebarHeader>
        <SidebarContent>
            <SidebarGroup>
                <SidebarGroupContent>
                    <SidebarMenu>
                        <SidebarMenuItem v-for="project in projects" :key="project.id">
                            <SidebarMenuButton asChild>
                            <Link :href="project.url" prefetch>
                                <component :is="getIcon(project.icon)" />
                                <span>{{project.name}}</span>
                            </Link>
                            </SidebarMenuButton>
                        </SidebarMenuItem>
                    </SidebarMenu>
                </SidebarGroupContent>
            </SidebarGroup>
        </SidebarContent>
    </Sidebar>
</template>
