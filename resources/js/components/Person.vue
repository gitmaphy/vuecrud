<template>
    <div >
        <nav aria-label="Page navigation example" style="margin-top: 2rem;">
            <ul class="pagination">
                <li class="page-item" v-bind:class="[{disabled: !pagination.prev_page_url}]"><a class="page-link" href="#" @click="fetchPersons(pagination.prev_page_url)">Previous</a></li>
                <li class="page-item disabled"><a class="page-link text-dark" href="#">Page {{ pagination.current_page }} of {{ pagination.last_page}}</a></li>
                <li class="page-item" v-bind:class="[{disabled: !pagination.next_page_url}]"><a class="page-link" href="#" @click="fetchPersons(pagination.next_page_url)">Next</a></li>
                <li class="page-item" v-bind:class="[{disabled: !pagination.next_page_url}]">
                    <a class="page-link btn-info btn" data-toggle="modal" data-target="#personModal" @click="viewModalPerson()" style="background-color: #6CB2EB;">Add New</a>
                </li>
            </ul>
        </nav>
        <div class="form-row">
            <div class="form-group col-md-3">
                <label for="registry_no_display">Age Filter</label>
                <div class="input-group">
                    <input type="number" class="form-control" placeholder="0-100" v-model="age_filter" >
                </div>
            </div>
            <div class="form-group col-md-9">
                <label for="registry_no_display">Filter by Lastname / Firstname</label>
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search" v-model="search_query" >
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" @click="fetchPersons('persons?search='+search_query)"><i class="fas fa-calendar-alt"></i>&nbsp;Search</button>
                    </div>
                </div>
            </div>

        </div>
        
        <div v-if="search_to_show != ''">
            <p>Results found for <strong>"{{search_to_show}}"</strong></p>
        </div>
        <div v-if="age_filter_show == ''">
            <div class="card card-body mb-2" v-for="person in persons" v-bind:key="person.id">
                <h3>{{person.first_name}}, {{person.last_name}}</h3>
                <p>Birthdate: {{person.birthday}} | Age: <strong>{{person.age}}</strong></p>
                <hr>
                <div class="row col-sm-12">
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button type="button" class="btn btn-secondary btn-warning" @click="editPerson(person)" data-toggle="modal" data-target="#personModal">Edit</button>
                        <button type="button" class="btn btn-secondary btn-dark" @click="deletePerson(person.id)">Delete</button>
                    </div>
                </div>
                
            </div>
        </div>
        <div v-else>
            <div v-for="person in persons" v-bind:key="person.id">
                <div class="card card-body mb-2" v-if="age_filter_show == person.age">
                    <h3>{{person.first_name}}, {{person.last_name}}</h3>
                    <p>Birthdate: {{person.birthday}} | Age: <strong>{{person.age}}</strong></p>
                    <hr>
                    <div class="row col-sm-12">
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <button type="button" class="btn btn-secondary btn-warning" @click="editPerson(person)" data-toggle="modal" data-target="#personModal">Edit</button>
                            <button type="button" class="btn btn-secondary btn-danger" @click="deletePerson(person.id)">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal" tabindex="-1" role="dialog" id="personModal">
            <div class="modal-dialog modal-lg" role="document">
                <form class="mb-3" @submit.prevent="addPerson">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Person Information</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            
                                <div class="form-row">
                                    <div class="form-group col-md-4 col-sm-12">
                                        <input type="text" class="form-control" placeholder="Firstname" v-model="person.first_name">
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <input type="text" class="form-control" placeholder="Lastname" v-model="person.last_name">
                                    </div>
                                    <div class="form-group col-md-2 col-sm-12">
                                        <input type="text" class="form-control" placeholder="yyyy-mm-dd" v-model="person.birthday">
                                    </div>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    data() {
        return {
            persons: [],
            person: {
                id: '',
                first_name: '',
                last_name: '',
                birthday: ''
            }, 
                person_id: '',
                pagination: {},
                edit: false,
                search_query: '',
                search_to_show: '',
                age_filter_show: '',
                age_filter: ''
        }
    },

    created() {
        this.fetchPersons();
    },

    methods: {
        fetchPersons(page_url) {
            let vm = this;
            page_url = page_url || '/api/persons'
            fetch(page_url)
                .then(res => res.json())
                .then(res => {
                    this.persons = res.data;
                    this.search_to_show = this.search_query;
                    this.age_filter_show = this.age_filter
                    vm.makePagination(res.meta,res.links,this.search_query);
                    console.log(res);
                    
                })
                .catch(err => console.log(err));
                
        },
        makePagination(meta, links, search_query) {
            let pagination = {
                current_page: meta.current_page,
                last_page: meta.last_page,
                next_page_url: links.next,
                prev_page_url: links.prev
            }
            if (search_query != '') {
                if (pagination.prev_page_url != null) {  pagination.prev_page_url += '&&search='+search_query; }
                if (pagination.next_page_url != null) { pagination.next_page_url += '&&search='+search_query; }
            }
            console.log(pagination);
            this.pagination = pagination;
        }, 
        deletePerson(id){
            if(confirm('Are you sure you want to delete this record?')) {
                fetch(`api/person/${id}`, {
                    method: 'delete'
                })
                .then(res => res.json())
                .then(data => {
                    alert('Article Removed');
                    this.fetchPersons();
                })
                .catch(err => console.log(err));
            }
        },
        addPerson() {
            if(this.edit == false) {
                // Add
                
                fetch('api/person', {
                    method: 'post',
                    body: JSON.stringify(this.person),
                    headers:  {
                        'content-type': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    this.person.last_name = '';
                    this.person.first_name = '';
                    this.person.birthday = '';

                    alert('Save successfull');
                    this.fetchPersons();

                })
                .catch(err=> console.log(err));
            } else {

                fetch('api/person', {
                    method: 'put',
                    body: JSON.stringify(this.person),
                    headers:  {
                        'content-type': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    this.person.last_name = '';
                    this.person.first_name = '';
                    this.person.birthday = '';

                    alert('Article Updated');
                    this.fetchPersons();
                    this.edit = false;

                })
                .catch(err=> console.log(err));

            }
        },
        editPerson(person) {
            this.edit = true;
            this.person.id = person.id;
            this.person.person_id = person.id;
            this.person.first_name = person.first_name;
            this.person.last_name = person.last_name;
            this.person.birthday = person.birthday;
        },
        viewModalPerson() {
            this.person.last_name = '';
            this.person.first_name = '';
            this.person.birthday = '';
            this.edit = false;
        }
    }
}

$('[data-toggle=datepicker]').each(function() {
    console.log('test');
    var target = $(this).data('target-name');
    var t = $('input[name=' + target + ']');
    t.datepicker({
      dateFormat: 'yy-mm-dd',
    });
    $(this).on("click", function() {
      t.datepicker("show");
    });
  });
</script>

