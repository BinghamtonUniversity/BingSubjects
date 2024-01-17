ajax.get('/api/data_types',function(data) {
    data = data.reverse();
    gdg = new GrapheneDataGrid(
        {el:'#adminDataGrid',
    name: 'data_types',
    search: false,columns: false,upload:false,download:false,title:'data_types',
    entries:[],
    sortBy: 'order',
    actions:actions,
    count:20,
    schema:[
        {name:"id",type:"hidden"},
        {
            "name": "type",
            "label": "Type",
            "type":"text",
        },
        {
            "name": "description",
            "label": "Description",
            "type":"text",
        },

    ],
    data: data
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
    }).on('model:data_types_study',function(grid_event){
        window.location = '/data_types/'+grid_event.model.attributes.id+'/studies';
        // console.log(grid_event.model.attributes.id)
    });
});
