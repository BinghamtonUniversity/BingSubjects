ajax.get('/api/studies/'+id+'/data_types',function(data) {
    console.log(data);
    //data = data.reverse();
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
                    name:"category",
                    type:"select",
                    label:"Category",
                    //template:"{{attributes.category}}",
                    options: [
                        {
                            label:"Assessment",
                            value:"assessment",
                        },
                        {
                            label:"Behavioral",
                            value:"behavioral",
                        },
                        {
                            label:"Neurosignal",
                            value:"neurosignal",
                        },
                        {
                            label:"Biospecimen",
                            value:"biospecimen",
                        }
                    ],
                    
                    // format: {
                    //     label:"{{first_name}} {{last_name}}",
                    //     value:"{{id}}",
                    //     display:"{{first_name}} {{last_name}}" +
                    //         '<div style="color:#aaa">{{email}}</div>'
                    // }
                },
                {
                    name:"type",
                    type:"text",
                    label:"Type",
                },
                {
                    name:"description",
                    type:"text",
                    label:"Description",
                }
            ],
            data:data
        }).on("model:created",function(grid_event) {
            ajax.post('/api/studies/'+id+'/data_types',grid_event.model.attributes,function(data) {
                grid_event.model.update(data)
            },function(data) {
                grid_event.model.undo();
            });
        }).on('model:edited',function (grid_event) {
            console.log(grid_event.model.attributes);
            ajax.put('/api/studies/'+id+'/data_types/'+grid_event.model.attributes.id,grid_event.model.attributes,function(data) {
                grid_event.model.update(data)
            },function(data) {
                grid_event.model.undo();
            });
        }).on("model:deleted",function(grid_event) {
            ajax.delete('/api/studies/'+id+'/data_types/'+grid_event.model.attributes.id,{},function(data) {},function(data) {
                grid_event.model.undo();
            });
        });
});
