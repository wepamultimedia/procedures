<script>
import MainLayout from "@pages/Vendor/Core/Backend/Layouts/MainLayout/MainLayout.vue";

export default {
    layout: (h, page) => h(MainLayout, {
        title: "Categories",
        icon: "view-list",
        bc: [{label: "procedures"}, {label: "procedures"}]
    }, () => page)
};
</script>
<script setup>
import {Link, router} from "@inertiajs/vue3";
import Table from "@core/Components/Table.vue";
import Icon from "@core/Components/Heroicon.vue";

const props = defineProps(["procedures", "categories"]);

const updatePosition = (item, position) => {
    router.put(route("admin.procedures.procedures.position", {
        procedure: item.id,
        position: position
    }), {}, {
        preserveState: true,
        preserveScroll: true
    });
}
</script>

<template>
    <div class="flex justify-between my-0 items-center h-14 rounded-lg overflow-hidden my-6">
        <span class="text-skin-base  font-medium text-xl">{{ __("procedures_list") }}</span>
        <Link :href="route('admin.procedures.procedures.create')"
              as="button"
              class="btn btn-success text-sm"
              type="button">{{ __("create") }}
        </Link>
    </div>
    <div class="w-full
                    bg-white dark:bg-gray-700
                    overflow-hidden
                    shadow
                    text-skin-base
                    rounded-lg
                    mb-20">
        <Table :columns="['name', {name:'category', show: 'sm'}]"
               :data="procedures"
               delete-route="admin.procedures.procedures.destroy"
               divide-x
               edit-route="admin.procedures.procedures.edit"
               even
               search-route="admin.procedures.procedures.index">
            <template #col-content-name="{item}">
                {{ item.name }}
                <p class="uppercase text-gray-700 dark:text-gray-300 text-xs py-0 sm:hidden">{{ item.category.name }}</p>
            </template>
            <template #col-content-category="{item}">
                <span :title="item.category.label">{{ item.category.name }}</span>
            </template>
        </Table>
    </div>
</template>

<style scoped>

</style>
