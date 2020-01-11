<template>
  <div>
      <el-date-picker
              v-model="data.value"
              value-format="yyyy-MM-dd"
              type="date"
              v-if="data.type=='single_line_date'"
              :placeholder="data.notes">
      </el-date-picker>
      <el-time-picker
              v-model="data.value"
              value-format="HH:mm:ss"
              type="date"
              v-if="data.type=='single_line_time'"
              :placeholder="data.time">
      </el-time-picker>
  </div>
</template>
<script>

  export default {
    name:'DateTimePicker',
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