ajax.get('/api/data_types',function(data) {
    data = data.reverse();
    // console.log(data)
    gdg = new GrapheneDataGrid(
        {el:'#adminDataGrid',
            name:'data_types',
            search:true,columns:false,upload:false,download:false,title:'data_types',
            // entries:[],
            sortBy:'order',
            actions:actions,
            count:20,
            schema:[
                {name:"id",type:"hidden"},
                {
                    name:"category",
                    label:"Category",
                    type:"select",
                    options: [
                        {
                            label:"Assessment",
                            value:"Assessment",
                        },
                        {
                            label:"Behavioral",
                            value:"Behavioral",
                        },
                        {
                            label:"Neurosignal",
                            value:"Neurosignal",
                        },
                        {
                            label:"Biospecimen",
                            value:"Biospecimen",
                        }
                    ]
                },
                {
                    name:"type",
                    label:"Type",
                    type:"text",
                }
            ],
            data:data
    }).on("model:edited",function(grid_event) {
        ajax.put('/api/data_types/'+grid_event.model.attributes.id,grid_event.model.attributes,function(data) {
            grid_event.model.update(data)
        },function(data) {
            grid_event.model.undo();
        });
    }).on("model:created",function(grid_event) {
        ajax.post('/api/data_types',grid_event.model.attributes,function(data) {
            grid_event.model.update(data)
        },function(data) {
            grid_event.model.undo();
        });
    }).on("model:deleted",function(grid_event) {
        ajax.delete('/api/data_types/'+grid_event.model.attributes.id,{},function(data) {},function(data) {
            grid_event.model.undo();
        });
    });
});
