ajax.get('/api/reports/'+id+'/execute',function(data) {
    console.log(data)
    mySchema = _.map(data[0],function(a,b){
        return {"type":"text","name":b}
    })
    gdg = new GrapheneDataGrid({el:'#adminDataGrid',
        item_template: gform.stencils['table_row'],
        search: true,columns: false,upload:false,download:true,title:'Report',
        entries:[],
        actions:[],
        count:100,
        schema:mySchema,
        data: data
    })
},function(err){
    console.log(err)
});

