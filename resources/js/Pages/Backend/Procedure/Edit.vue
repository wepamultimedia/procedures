<script>
import MainLayout from "@pages/Vendor/Core/Backend/Layouts/MainLayout/MainLayout.vue";

export default {
    layout: (h, page) => h(MainLayout, {
        title: "procedure",
        icon: "view-list",
        bc: [{label: 'procedures'}, {
            label: "procedures",
            route: "admin.procedures.procedures.index"
        }, {label: "update"}]
    }, () => page)
};
</script>
<script setup>
import {__} from "@core/Mixins/translations";
import {useForm} from "@inertiajs/vue3";
import SaveFormButton from "@core/Components/Form/SaveFormButton.vue";
import Input from "@core/Components/Form/Input.vue";
import {reactive, ref} from "vue";
import Select from "@core/Components/Select.vue";
import Ckeditor from "@core/Components/Form/Ckeditor.vue";
import {useStore} from "vuex";
import InputFile from "@core/Components/Form/InputFile.vue";
import Table from "@core/Components/Table.vue";
import Flap from "@core/Components/Flap.vue";
import Icon from "@core/Components/Heroicon.vue";
import {timestamp} from "@vueuse/core";

const props = defineProps(["procedure", "categories", "errors"]);

const selectedLocale = ref();

const store = useStore();
const form = useForm({
    id: props.procedure.id,
    name: props.procedure.name,
    body: props.procedure.body,
    category_id: props.procedure.category_id,
});

const procedureFiles = reactive({
    flap: false,
    store: file => {
        procedureFiles.form.file_url = file.url;
        procedureFiles.form.name = file.name;
        procedureFiles.form.procedure_id = form.id;
        procedureFiles.form.post(route("admin.procedures.files.store"), {
            preserveState: false,
            preserveScroll: true,
            onFinish: () => {
                procedureFiles.close();
            }
        });
    },
    edit: file => {
        procedureFiles.flap = true;
        procedureFiles.form.id = file.id;
        procedureFiles.form.file_url = file.file_url;
        procedureFiles.form.name = file.name;
        procedureFiles.form.procedure_id = form.id;
    },
    update: () => {
        procedureFiles.form.put(route("admin.procedures.files.update", {file: procedureFiles.form.id}), {
            preserveState: false,
            preserveScroll: true,
            onFinish: () => {
                procedureFiles.close();
            }
        });
    },
    close: () => {
        procedureFiles.flap = false;
        procedureFiles.form.reset('id', 'file_url', 'name');
    },
    position: (file, position) => {
        router.put(route("admin.procedures.procedure.files.position", {
            file: file.id,
            position: position
        }), {}, {
            preserveState: true,
            preserveScroll: true
        });
    },
    form: useForm({
        id: null,
        procedure_id: form.id,
        file_url: null,
        name: null,
    })
});

function submit() {
    form.put(route("admin.procedures.procedures.update", {procedure: props.procedure.id}), {
        preserveState: false,
        preserveScroll: true,
        onSuccess: () => store.dispatch("backend/addAlert", {type: "success", message: __("saved")}),
        onError: () => store.dispatch("backend/addAlert", {type: "error", message: props.errors})
    });
}
</script>

<template>
    <div class="flex justify-between my-0 items-center h-14 rounded-lg overflow-hidden mt-4">
        <span class="dark:text-light font-medium text-xl">{{ __("edit_title") }}</span>
    </div>
    <form class="pb-8"
          @submit.prevent="submit">
        <div class="text-skin-base
                    border
                    dark:border-gray-600
                    bg-white dark:bg-gray-600
                    rounded-lg
                    shadow">
            <div class="grid grid-cols-12 divide-y xl:divide-x divide-gray-300 dark:divide-gray-700">
                <!-- title, summary and body-->
                <div class="p-6 col-span-full xl:col-span-8">
                    <div class="mb-6">
                        <Input v-model="form"
                               v-model:locale="selectedLocale"
                               :errors="form.errors"
                               :label="__('name')"
                               autofocus
                               name="name"
                               required/>
                    </div>
                    <div class="mb-6">
                        <div class="mt-1"
                             style="--ck-border-radius: 0.50rem">
                            <Ckeditor v-model="form"
                                      v-model:locale="selectedLocale"
                                      :errors="form.errors"
                                      :label="__('body')"
                                      name="body"
                                      required></Ckeditor>
                        </div>
                    </div>
                </div>
                <!-- draf, date, category and cover -->
                <div class="col-span-full xl:col-span-4 grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-1 divide-y lg:divide-x lg:divide-y-0 xl:divide-y xl:divide-x-0 divide-gray-300 dark:divide-gray-700 gap-4">
                    <!-- draft, date and category -->
                    <div class="p-6">
                        <!--                        <div class="mb-6">-->
                        <!--                            <label class="text-sm">{{ __("draft") }}</label>-->
                        <!--                            <ToggleButton v-model="form.draft"/>-->
                        <!--                        </div>-->
                        <div>
                            <Select v-model="form.category_id"
                                    :errors="errors"
                                    :label="__('select_category')"
                                    :options="categories"
                                    name="category_id"
                                    option-label="label"
                                    reduce
                                    required></Select>
                        </div>
                    </div>
                </div>
            </div>
            <!-- seo -->
            <div class="rounded-b-lg overflow-hidden">
                <div class="p-3 bg-gray-200 dark:bg-gray-500 flex justify-end">
                    <SaveFormButton :form="form"/>
                </div>
            </div>
        </div>
    </form>

    <!-- Procedure Files -->
    <section class="my-8">
        <div class="flex justify-between my-0 items-center h-14 rounded-lg overflow-hidden mt-6">
            <span class="text-skin-base font-medium text-lg">{{ __("files") }}</span>
            <InputFile :button-label="__('add_file')"
                       button-class="btn btn-secondary"
                       class="flex justify-end"
                       name="cover"
                       @change="procedureFiles.store"/>
        </div>
        <div class="text-skin-base
                    border
                    overflow-hidden
                    dark:border-gray-600
                    bg-white dark:bg-gray-600
                    rounded-lg
                    shadow">
            <Table :columns="['name']"
                   :key="timestamp()"
                   :data="procedure.files"
                   :delete-message="__('delete_file_message')"
                   :delete-title="__('delete_file_title')"
                   delete-route="admin.procedures.files.destroy"
                   divide-x
                   even>
                <template #action-edit="{item}">
                    <button class="w-8 h-6"
                            @click="procedureFiles.edit(item)">
                        <icon class="fill-skin-base w-5 h-5"
                              icon="pencil-alt"></icon>
                    </button>
                </template>
            </Table>
        </div>

        <!-- Prices flap -->
        <Flap v-model="procedureFiles.flap"
              :on-close="procedureFiles.close"
              close-background
              md>
            <form class="pb-8"
                  @submit.prevent="procedureFiles.update">
                <h2>{{ __("edit_file") }}</h2>
                <div class="my-6">
                    <Input v-model="procedureFiles.form"
                           :errors="procedureFiles.form.errors"
                           :label="__('name')"
                           autofocus
                           name="name"
                           required/>
                </div>
                <div class="rounded-b-lg ">
                    <SaveFormButton :form="procedureFiles.form"/>
                </div>
            </form>
        </Flap>
    </section>
</template>

<style scoped>

</style>
