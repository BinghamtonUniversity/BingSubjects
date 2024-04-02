ajax.get('/api/studies/'+id+'/data_types',function(data) {
    console.log(data);
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
                    name:"category",
                    type:"select",
                    label:"Category",
                    options:[
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
                    ],
                },
                {
                    name:"type",
                    type:"select",
                    label:"Type",
                    options:"/api/data_types",
                    format: {
                        label:"{{type}}",
                        value:"{{type}}"
                    }
                },
                {
                    name:"description",
                    type:"text",
                    label:"Description",
                }
            ],
            data:data
        }).on("create",function(grid_event) {
            grid_event.preventDefault();
            new gform({
                "legend" : "Add Data Type to Study",
                "fields": [
                    {name:"id",type:"hidden"},
                    {
                        name:"data_type_id",
                        label:"Type",
                        type:"user",
                        options:"/api/data_types",
                        format:{
                            label:"{{type}}",
                            value:"{{id}}",
                            display:"{{type}}" +
                                '<div style="color:#aaa">{{category}}</div>'
                        }
                    },
                    {
                        name:"description",
                        type:"text",
                        label:"Description",
                    }
                ]
            }).on('save',function(form_event) {
                if(form_event.form.validate()) {
                    form_data = form_event.form.get();
                    form_event.form.trigger('close');
                    ajax.post('/api/studies/'+id+'/data_types/'+form_data.data_type_id,form_data,function(data) {
                        //refresh page
                        //grid_event.model.update(data);
                    });
                }
            }).on('cancel',function(form_event) {
                form_event.form.trigger('close');
            }).modal();
        }).on("edit",function(e) {
            e.preventDefault();
        }).on("model:edit",function(grid_event) {
            new gform({
                "legend" : "Update Description for this study's "+grid_event.model.attributes.type,
                "fields": [
                    {name:"id",type:"hidden"},
                    {
                        name:"description",
                        type:"text",
                        label:"Description",
                    }
                ]
            }).on('save',function(form_event) {
                if(form_event.form.validate()) {
                    form_event.form.trigger('close');
                    form_data = form_event.form.get();
                    ajax.put('/api/studies/'+id+'/data_types/'+form_data.id,form_data,function(data) {
                        grid_event.model.update(data);
                    },function(data) {
                        grid_event.model.undo();
                    });
                }
            }).on('cancel',function(form_event) {
                form_event.form.trigger('close');
            }).modal().set(grid_event.model.attributes);
        }).on("model:deleted",function(grid_event) {
            ajax.delete('/api/studies/'+id+'/data_types/'+grid_event.model.attributes.id,{},function(data) {},function(data) {
                grid_event.model.undo();
            });
        });
});
