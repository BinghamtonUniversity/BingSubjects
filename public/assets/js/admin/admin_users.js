ajax.get('/api/users',function(data) {
    // data = data.reverse();
    gdg = new GrapheneDataGrid(
        {el:'#adminDataGrid',
            name: 'users',
            search: false,columns: false,upload:false,download:false,title:'users',
            entries:[],
            sortBy: 'order',
            actions:actions,
            count:20,
            schema:[
                {name:"id",type:"hidden"},
                {name:'first_name', type:'text', label: "First Name"},
                {name:'last_name', type:'text', label: "Last Name"},
                {name:'email', type:'email', label: "Email"},
                {name:'password', type:'password', label: "Password"},
            ],
            data: data
        })
        .on("model:created",function(grid_event) {
            // console.log(grid_event.model.attributes)
            ajax.post('/api/users', grid_event.model.attributes,function(data) {
                grid_event.model.update(data)
            },function(data) {
                grid_event.model.undo();
            });
        })
        .on('model:edited',function (grid_event){
            ajax.put('/api/users/'+grid_event.model.attributes.id,grid_event.model.attributes,function(data) {},function(data) {
                grid_event.model.undo();
            });
        })
        .on("model:deleted",function(grid_event) {
            ajax.delete('/api/users/'+grid_event.model.attributes.id,{},function(data) {},function(data) {
                grid_event.model.undo();
            });
        })

});
