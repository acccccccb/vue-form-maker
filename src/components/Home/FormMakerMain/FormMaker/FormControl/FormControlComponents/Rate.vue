<template>
    <div>
        <el-rate v-if="data.type=='single_line_rate'" v-model.number="data.value"></el-rate>
    </div>
</template>
<script>

    export default {
        name:'Rate',
        props:{
            data:{
                require:true
            }
        },
        data(){
            return {

            }
        },
        methods:{

        }
    }
</script>
<style scoped>

</style>