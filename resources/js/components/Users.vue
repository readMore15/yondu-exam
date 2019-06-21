<template>
    <div>
        <div class="row mb-2">
            <div class="col-md-6 d-flex">
                <h2 class="align-self-center mr-2">Users</h2>
                <button @click="clearFields" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Add New</button>
            </div>
            <div class="col-md-6">
                <nav aria-label="Page navigation example pull-right">
                    <ul class="pagination pull-right">
                        <li v-bind:class=[{disabled:!pagination.prev_page_url}] class="page-item">
                            <a @click="fetchUsers(pagination.prev_page_url)" class="page-link" href="#">Previous</a>
                        </li>
                        <li class="page-item disabled"><a href="" class="page-link text-dark">Page <strong>{{ pagination.current_page }}</strong> of {{ pagination.last_page }}</a></li>
                        <li v-bind:class=[{disabled:!pagination.next_page_url}] class="page-item">
                            <a @click="fetchUsers(pagination.next_page_url)" class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12" id="splash-message"></div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th width="5%"><button @click="deleteUsers" class="btn btn-danger">Apply</button></th>
                                    <th>ID</th>
                                    <th scope="col">First Name</th>
                                    <th scope="col">Last Name</th>
                                    <th scope="col">Registered</th>
                                    <th class="text-right">Actions</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="user in users" v-bind:key='user.id'>
                                    <td><input type="checkbox" :value='user.id' v-model="users_delete" /></td>
                                    <td>{{ user.id }}</td>
                                    <td>{{ user.first_name }}</td>
                                    <td>{{ user.last_name }}</td>
                                    <td>{{ user.created_at }}</td>
                                    <td><button @click="userView(user)" class="btn btn-info text-white" data-toggle="modal" data-target="#exampleModal">View <i class="fa fa-eye text-light" aria-hidden="true"></i></button></td>
                                    <td><button @click="userDelete(user.id)" class="btn btn-danger">Delete <i class="fa fa-trash" aria-hidden="true"></i></button></td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <form @submit.prevent>
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">View User</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row mb-2">
                                                <label for="first_name" class="col-md-3">First Name</label>
                                                <div class="col-md-9">
                                                    <input type="text" v-model="user.first_name" id="first_name" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <label for="last_name" class="col-md-3">Last Name</label>
                                                <div class="col-md-9">
                                                    <input type="text" v-model="user.last_name" id="last_name" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <label for="address" class="col-md-3">Address</label>
                                                <div class="col-md-9">
                                                    <input type="text" v-model="user.address" id="address" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <label for="postcode" class="col-md-3">Postcode</label>
                                                <div class="col-md-9">
                                                    <input type="text" v-model="user.postcode" id="postcode" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <label for="phone" class="col-md-3">Phone</label>
                                                <div class="col-md-9">
                                                    <input type="text" v-model="user.phone" id="phone" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <label for="email" class="col-md-3">Email</label>
                                                <div class="col-md-9">
                                                    <input type="text" v-model="user.email" id="email" class="form-control" />
                                                </div>
                                            </div>
                                            <hr/>
                                            <div class="row mb-2">
                                                <label for="username" class="col-md-3">Username</label>
                                                <div class="col-md-9">
                                                    <input type="text" v-model="user.username" id="username" class="form-control" autocomplete="false"/>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <label for="password" class="col-md-3">Password</label>
                                                <div class="col-md-9">
                                                    <input type="password" v-model="user.password" id="password" class="form-control" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button @click="userUpdate" type="button" class="btn btn-success">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                'users': [],
                'user': {
                    'first_name': '',
                    'last_name': '',
                    'address': '',
                    'postcode': '',
                    'phone': '',
                    'email': '',
                    'username': '',
                    'password': ''
                },
                'user_id': '',
                'pagination': {},
                'users_delete': [],
                'edit': false
            }
        },

        created() {
            this.fetchUsers();
        },

        methods: {
            fetchUsers(page) {
                let pg = this;
                let page_url = page || 'api/users';

                fetch(page_url)
                    .then(res => res.json())
                    .then(res => {
                        this.users = res.data;
                        pg.makePagination(res.meta, res.links);
                    })
                    .catch(err => console.log(err));
            },

            makePagination(meta, links) {
                let pagination = {
                    current_page: meta.current_page,
                    last_page: meta.last_page,
                    next_page_url: links.next,
                    prev_page_url: links.prev
                }

                this.pagination = pagination;
            },

            userDelete(id) {
                if(confirm('Are you sure to delete this user?')) {
                    fetch(`api/user/${id}`, {
                        method: 'delete'
                    })
                    .then(res => res.json())
                    .then(res => {
                        let message = document.getElementById('splash-message');
                        message.classList.add('alert');
                        message.classList.add('alert-danger');
                        message.innerHTML = 'User successfully removed!';

                        this.fetchUsers();

                        setTimeout(() => { 
                            message.classList.add('animated');
                            message.classList.add('flipOutX');
                            }, 3000);

                        setTimeout(() => { 
                            message.textContent = "";
                            message.classList.remove('alert');
                            message.classList.remove('alert-danger');
                         }, 4000);

                        setTimeout(() => { 
                            message.classList.remove('animated');
                            message.classList.remove('flipOutX');
                        }, 5000);
                    })
                    .catch(err => console.log(err));
                }
            },

            userUpdate() {
                if(this.edit === false) {
                    // add user
                    fetch('api/user', {
                        method: 'post',
                        body: JSON.stringify(this.user),
                        headers: {
                            'content-type': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        let modalShadow = document.getElementsByClassName('modal-backdrop')[0];
                        let formModal = document.getElementById('exampleModal');
                        formModal.classList.add('animated');
                        formModal.classList.add('bounceOutUp');
                        formModal.classList.add('fade');
                        formModal.style.display = 'none';
                        modalShadow.remove();

                        this.clearFields();

                        let message = document.getElementById('splash-message');
                        message.classList.add('alert');
                        message.classList.add('alert-success');
                        message.innerHTML = 'User successfully added!';

                        this.fetchUsers();

                        setTimeout(() => { 
                            message.classList.add('animated');
                            message.classList.add('flipOutX');
                            }, 3000);

                        setTimeout(() => { 
                            message.textContent = "";
                            message.classList.remove('alert');
                            message.classList.remove('alert-success');
                        }, 4000);

                        setTimeout(() => { 
                            message.classList.remove('animated');
                            message.classList.remove('flipOutX');
                        }, 5000);
                    })
                    .catch(err => console.log(err));

                } else {
                    // update
                    fetch('api/user', {
                        method: 'put',
                        body: JSON.stringify(this.user),
                        headers: {
                            'content-type': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        let modalShadow = document.getElementsByClassName('modal-backdrop')[0];
                        let formModal = document.getElementById('exampleModal');
                        formModal.classList.add('animated');
                        formModal.classList.add('bounceOutUp');
                        formModal.classList.add('fade');
                        formModal.style.display = 'none';
                        modalShadow.remove();

                        this.clearFields();

                        let message = document.getElementById('splash-message');
                        message.classList.add('alert');
                        message.classList.add('alert-success');
                        message.innerHTML = 'User successfully updated!';

                        this.fetchUsers();

                        setTimeout(() => { 
                            message.classList.add('animated');
                            message.classList.add('flipOutX');
                            }, 3000);

                        setTimeout(() => { 
                            message.textContent = "";
                            message.classList.remove('alert');
                            message.classList.remove('alert-success');
                        }, 4000);

                        setTimeout(() => { 
                            message.classList.remove('animated');
                            message.classList.remove('flipOutX');
                        }, 5000);
                    })
                    .catch(err => console.log(err));
                }
            },

            userView(user) {
                this.edit = true;

                this.user.id = user.id;
                this.user.user_id = user.id;
                this.user.first_name = user.first_name;
                this.user.last_name = user.last_name;
                this.user.address = user.address;
                this.user.postcode = user.postcode;
                this.user.phone = user.phone;
                this.user.email = user.email;
                this.user.username = user.username;
                this.user.password = user.password;
            },

            clearFields() {
                this.user.first_name = '';
                this.user.last_name = '';
                this.user.address = '';
                this.user.postcode = '';
                this.user.phone = '';
                this.user.email = '';
                this.user.username = '';
                this.user.password = '';
            },

            deleteUsers() {
                // console.log(JSON.stringify(this.users_delete));

                fetch('api/multipledelete', {
                        method: 'post',
                        body: JSON.stringify({
                            'users': this.users_delete
                        }),
                        headers: {
                            'content-type': 'application/json'
                        }
                    })
                    .then(data => {
                        let message = document.getElementById('splash-message');

                        message.classList.add('alert');
                        message.classList.add('alert-danger');
                        message.innerHTML = 'Users successfully deleted!';

                        this.fetchUsers();

                        setTimeout(() => { 
                            message.classList.add('animated');
                            message.classList.add('flipOutX');
                            }, 3000);

                        setTimeout(() => { 
                            message.textContent = "";
                            message.classList.remove('alert');
                            message.classList.remove('alert-danger');
                        }, 4000);

                        setTimeout(() => { 
                            message.classList.remove('animated');
                            message.classList.remove('flipOutX');
                        }, 5000);
                    })
                    .catch(err => console.log(err));
            }
        },

        
    }
</script>
