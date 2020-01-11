<template>
  <div>
      <el-checkbox-group :name="data.name" :placeholder="data.notes" v-model="data.value">
          <div v-for="(options,index) in data.choices" :key="index"><el-checkbox :label="options.value">{{options.value}}</el-checkbox></div>
      </el-checkbox-group>
  </div>
</template>
<script>

  export default {
    name:'MultipleChoice',
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