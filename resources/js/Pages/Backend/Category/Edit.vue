<script>
import MainLayout from "@pages/Vendor/Core/Backend/Layouts/MainLayout/MainLayout.vue";

export default {
    layout: (h, page) => h(MainLayout, {
        title: "Categories",
        icon: "view-list",
        bc: [{label: "procedures"}, {label: "categories", route: "admin.procedures.categories.index"}, {label: "edit"}]
    }, () => page)
};
</script>
<script setup>
import {__} from "@core/Mixins/translations";
import {Link, useForm} from "@inertiajs/vue3";
import SaveFormButton from "@core/Components/Form/SaveFormButton.vue";
import Input from "@core/Components/Form/Input.vue";
import {ref} from "vue";
import Table from "@core/Components/Table.vue";
import Icon from "@core/Components/Heroicon.vue";

const props = defineProps(["category", "categories", "parents", "hasProcedures"]);

const selectedLocale = ref();

const form = useForm({
    ...props.category,
});

function submit() {
    form.put(route("admin.procedures.categories.update", {category: props.category.id}), {
        preserveScroll: true,
        preserveState: false,
        onSuccess: () => store.dispatch("backend/addAlert", {type: "success", message: __("saved")}),
        onError: () => store.dispatch("backend/addAlert", {type: "error", message: form.errors})
    });
}
</script>

<template>
    <div class="flex justify-between my-0 items-center h-14 rounded-lg overflow-hidden mt-4">
        <span class="dark:text-light font-medium text-xl">{{ __("create_title") }}</span>
    </div>
    <form @submit.prevent="submit">
        <div class="max-w-7xl">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 text-skin-base ">
                <div class="col-span-1">
                    <p class="text-sm"
                       v-html="__('create_summary')"></p>
                </div>

                <div class="col-span-2
                        border
                        dark:border-gray-600
                        bg-white dark:bg-gray-600
                        rounded-lg
                        shadow">
                    <div class="grid grid-cols-6 p-6">
                        <div class="col-span-6 sm:col-span-6 lg:col-span-5 xl:col-span-4 mb-6">
                            <span v-if="parents.length"
                                  class="font-semibold">{{ __('subcategory_of') }}:</span>
                            <ul v-if="parents.length"
                                class="mb-2 flex">
                                <li v-for="(parent, index) in parents"
                                    :key="parent.id"
                                    class="text-sm">
                                    <Link :href="route('admin.procedures.categories.edit', {category: parent.id})">
                                        {{ parent.name }} <span v-if="(index + 1) < parents.length"
                                                                class="px-2">/</span>
                                    </Link>
                                </li>
                            </ul>
                            <Input v-model="form"
                                   :key="category.id"
                                   v-model:locale="selectedLocale"
                                   :errors="form.errors"
                                   :label="__('name')"
                                   autofocus
                                   name="name"
                                   required/>
                            <p v-if="hasProcedures"
                               class="text-sm text-gray-400">{{ __('has_procedures', {category: category.name}) }}</p>
                        </div>
                    </div>
                    <div class="rounded-b-lg overflow-hidden">
                        <div class="p-3 bg-gray-200 dark:bg-gray-500 flex justify-end">
                            <SaveFormButton :form="form"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div>
        <div class="flex justify-end mt-8" v-if="!hasProcedures">
            <Link :href="route('admin.procedures.categories.create', {parent: category.id})"
                  as="button"
                  class="btn btn-info text-sm"
                  type="button">{{ __("create_subcategory") }}
            </Link>
        </div>
        <div class="w-full
                    bg-white dark:bg-gray-700
                    overflow-hidden
                    shadow
                    text-skin-base
                    rounded-lg
                    mb-20 mt-4">
            <Table :columns="['name', 'position']"
                   :data="categories"
                   delete-route="admin.procedures.categories.destroy"
                   divide-x
                   edit-route="admin.procedures.categories.edit"
                   even
                   search-route="admin.procedures.categories.index">
                <template #col-content-position="{item}">
                    <div class="flex items-center justify-start">
                        <div class="inline-flex"
                             role="group">
                            <button class="rounded-l-lg px-1 py-2.5 bg-gray-400 text-white font-medium text-xs leading-tight uppercase hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring-0 active:bg-gray-800 transition duration-150 ease-in-out"
                                    type="button"
                                    @click="updatePosition(item, item.position - 1)">
                                <Icon class="m-0 fill-white h-4 w-4"
                                      icon="chevron-up"></Icon>
                            </button>
                            <span class="px-2 py-2.5 bg-gray-400 text-white font-medium text-xs leading-tight uppercase"
                                  type="button">
                            {{ item.position }}
                        </span>
                            <button class="rounded-r-lg px-1 py-2.5 bg-gray-400 text-white font-medium text-xs leading-tight uppercase hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring-0 active:bg-gray-800 transition duration-150 ease-in-out"
                                    type="button"
                                    @click="updatePosition(item, item.position + 1)">
                                <Icon class="m-0 fill-white h-4 w-4"
                                      icon="chevron-down"></Icon>
                            </button>
                        </div>
                    </div>
                </template>
            </Table>
        </div>
    </div>
</template>

<style scoped>

</style>
