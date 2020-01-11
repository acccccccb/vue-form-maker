<template>
  <div>
      <el-input
              :name="data.name"
              v-model="data.value"
              type="textarea"
              :placeholder="data.notes"
              :autosize="{ minRows: 5, maxRows:10}"
              v-if="data.type=='paragraph_text'">
      </el-input>
      <el-input
              :name="data.name"
              v-model="data.value"
              type="text"
              :placeholder="data.notes"
              v-if="data.type=='single_line_text'"></el-input>
      <el-input
              :name="data.name"
              v-model="data.value"
              type="text"
              :placeholder="data.notes"
              :maxlength="11"
              prefix-icon="el-icon-phone"
              v-if="data.type=='single_line_phone'"></el-input>
      <el-input-number
              :name="data.name"
              v-if="data.type=='single_line_number'"
              v-model.number="data.value"
              :min.number="data.min"
              :precision.number="data.precision"
              :step-strictly="true"
              :step.number="data.step"
              :max.number="data.max"></el-input-number>
  </div>
</template>
<script>

  export default {
    name:'InputText',
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