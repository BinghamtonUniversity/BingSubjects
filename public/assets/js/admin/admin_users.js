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
        .on('model:update_permissions',function(grid_event){
            // debugger
            // console.log(grid_event.model.attributes.permissions)
            gdg = new gform(
                {
                    name: 'permissions_form',
                    title:'User Permissions',
                    actions:[
                        {
                            "type": "save",
                            "action": "save",
                            "label": "Save"
                        }
                    ],
                    fields:[
                        {
                            "type": "radio",
                            "label": "Permissions",
                            "name": "permissions",
                            "multiple": true,
                            "showColumn": true,
                            "options": [
                                {
                                    "type": "optgroup",
                                    "options": [
                                        {
                                            "label":"View Studies",
                                            "value":"view_studies"
                                        },
                                        {
                                            "label":"Manage Studies",
                                            "value":"manage_studies"
                                        },
                                        {
                                            "label":"View PIs",
                                            "value":"view_pis",
                                        },
                                        {
                                            "label":"Manage PIs",
                                            "value":"manage_pis",
                                        },
                                        {
                                            "label":"View Permissions",
                                            "value":"view_permissions",
                                        },
                                        {
                                            "label":"Manage Permissions",
                                            "value":"manage_permissions",
                                        },
                                        {
                                            "label":"View Participants",
                                            "value":"view_participants",
                                        },
                                        {
                                            "label":"Manage Participants",
                                            "value":"manage_participants",
                                        },
                                        {
                                            "label":"Super User",
                                            "value":"super_user"
                                        }
                                    ]
                                }
                            ]
                        }
                    ],
                // data: data.permissions
                }).modal().on('save',function (perm_event){
                    ajax.put('/api/users/'+grid_event.model.attributes.id+'/permissions',perm_event.form.get(),function(perm_data) {
                        grid_event.model.attributes.permissions = perm_data
                        perm_event.form.trigger('close')
                    });
                }).set({permissions:grid_event.model.attributes.permissions})
        })
});