ajax.get('/api/studies/'+id+'/data_types',function(data) {
    data = data.reverse();
    gdg = new GrapheneDataGrid(
        {el:'#adminDataGrid',
            name:'study_data_types',
            search:false,columns:false,upload:false,download:false,title:'study_data_types',
            entries:[],
            sortBy:'order',
            actions:actions,
            count:20,
            schema:[
                {name:"id",type:"hidden"},
                {
                    name:"data_type_id",
                    type:"combobox",
                    label:"Data Type",
                    template:"{{attributes.data_type.type}}: {{attributes.data_type.description}}",
                    options:"/api/data_types",
                    format:{
                        label:"{{type}}: {{description}}",
                        value:"{{id}}",
                        display:"{{type}}" +
                            '<div style="color:#aaa">{{description}}</div>'
                    }
                }
            ],
            data:data
        }).on("model:created",function(grid_event) {
        ajax.post('/api/studies/'+id+'/data_types/'+grid_event.model.attributes.data_type_id, {},function(data) {
            grid_event.model.update(data)
        },function(data) {
            grid_event.model.undo();
        });
    }).on("model:deleted",function(grid_event) {
        ajax.delete('/api/studies/'+id+'/data_types/'+grid_event.model.attributes.data_type_id,{},function(data) {},function(data) {
            grid_event.model.undo();
        });
    });
});
