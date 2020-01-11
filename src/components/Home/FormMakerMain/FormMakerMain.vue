<template>
    <el-tabs v-model="activeName" type="card">
        <el-tab-pane label="创建表单" name="formMaker">
            <FormMaker></FormMaker>
        </el-tab-pane>
        <el-tab-pane label="表单列表" name="formMakerList">
            <FormMakerList></FormMakerList>
        </el-tab-pane>
    </el-tabs>
</template>

<script>
    import FormMaker from '../FormMakerMain/FormMaker'
    import FormMakerList from '../FormMakerMain/FormMakerList'
    export default {
        name: "FormMakerMain",
        components:{
            FormMaker,FormMakerList
        },
        data(){
            return {
                activeName:'formMaker'
            }
        },
    }
</script>

<style scoped>

</style>