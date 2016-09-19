@extends('lead.index')

@section('top')
    <div class="panel panel-default" >
        <div class="panel-heading">
            <h2 class="panel-title">References</h2>
        </div>
        <div class="panel-body">
        
        @if(!$lead->dnc)
            <form method="POST" action="/lead/{{ $lead->id }}/saveReference" role="form" class="form-inline" id="form">     
                <fieldset id="reference" style="display:none">
                    <ol>
                        <li>
                            <label>Date</label>
                            <input id="datepicker" type="text" id="dob" name="dob" placeholder="YYYY-mm-dd" value="{{ date('m/d/Y')}}">
                            <input type="image" id="calendar" src="/images/calendar.png">
                        </li>
                        <li>
                            <label>Name *</label>
                            <input type="text" id="name" name="name" required >
                        </li>
                        <li>
                            <label>Gender *</label>
                            <input type="radio" name="gender" id="gender" value="F" required> Female &nbsp;
                            <input type="radio" name="gender" id="gender" value="M"> Male
                        </li>
                        <li>
                            <label>Email</label>
                            <input type="text" id="email" name="email" value="">
                        </li>
                        <li>
                            <label>Mobile *</label>
                            <input type="text" id="mobile" name="mobile" required>
                        </li>
                        <li>
                            <label>How did you hear about us?</label>
                            <div class="dropdown">
                                <select id="voice" name="voice">
                                </select>
                            </div>
                        </li>
                        <li>
                            <label>Country</label>
                            <div class="dropdown">
                                <select id="country" onchange="selectState(this.options[this.selectedIndex].value)" name="country">
                                    <option value="">Select Country</option>
                                </select>
                            </div>
                        </li>
                        <li>
                            <label>State/Region</label>
                            <select id="state" onchange="selectCity(this.options[this.selectedIndex].value)" name="state">
                                <option value="">Select State</option>
                            </select>
                        </li>
                        <li>
                            <label>City</label>
                            <select id="city" name="city">
                                <option value="">Select City</option>
                            </select>
                        </li>
                        <li>
                            <label>Remarks</label>
                            <textarea rows="3" cols="50" id="remarks" name="remark" required>
                            </textarea>
                        </li>
                    @if(Auth::user()->hasRole('service') || Auth::user()->hasRole('service_tl') || Auth::user()->hasRole('marketing') || Auth::user()->hasRole('admin'))

                        <li>
                            <label>Sourced By</label>
                            <input type="text" id="sourced_by" name="sourced_by">
                        </li>
                    @else
                            <input type="hidden" name="sourced_by" value="{{ Auth::user()->employee->name }}">
                    @endif                  
                    </ol>
                </fieldset>                 
                <div  class="col-md-3">
                    <button type="submit" id="add" name="add" class="btn btn-primary">Add</button>
                    <button id="save" type="submit" name="save" class="btn btn-success"> Save</button> 
                    <button id="cancel" type="submit" name="cancel" class="btn btn-danger">Cancel</button>
                </div>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="source" value="10">
                <input type="hidden" name="referrer_clinic" value="{{ $lead->clinic }}">
                <input type="hidden" name="referrer_enquiry_no" value="{{ $lead->enquiry_no }}">
                <input type="hidden" name="referrer_id" value="{{ $lead->id }}">
            </form>

        @else
            <div class="blacklisted"></div>
        @endif
        </div>
    </div>

    <div class="panel panel-default" id='leadreference'>
        <div class="panel-heading">
            References
        </div>
         <div id="loader" v-show="loading" style="text-align:center" >
            <img src="/images/loading.gif">
         </div>
        <div class="panel-body" style='padding-left: 0px'>
            <table class="table table-bordered">
                <thead>                 
                    <tr>
                        <th>Lead Id</th>
                        <th>Patient Id</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Voice</th>
                        <th>Date</th>
                        <th>Sourced By</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody v-for="reference in references">
                  <tr is="reference-row" :reference.sync="reference" :selected.sync="selected"></tr>
                         
                    
               </tbody>
            </table>
           
            <a id='showBenefit' data-toggle="modal" data-target="#sModal" href="" style='display: none' class="btn btn-primary">Add Cart</a>
            <a id='showBox' data-toggle="modal" data-target="#myModal" href="" style='display: none' class="btn btn-primary">Add Cart</a>

           

        </div>

        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                         <h4 class="modal-title">Cart Info</h4>

                    </div>
                    <div class="modal-body"><div class="te">
                    <div class='row'>
                            <div class="col-sm-6">
                           <label>Shipping Address :</label>
                            <select class="form-control" v-model="shipping_address_id" id="shipping_address">
                                <option value="0">Same as Billing Address</option>
                                <option v-for="address in lead.addresses" :value="address.id">@{{ address.address_type }}</option>                  
                            </select>
                            </div>
                             <div class="col-sm-6">
                               <label>Seller :</label><br>
                                @if(Auth::user()->canCreateCartForOthers()) 

                                        <select v-model="cre_id">                  
                                    
                                            <option v-for="user in users" :value="user.id" :selected="lead.cre_name == user.name">
                                                @{{ user.name }}
                                            </option>
                                        </select>
                                @else
                                        <input type="hidden" v-model="cre_id" value="{{ Auth::id() }}">
                                        {{ Auth::user()->employee->name }}
                                        
                                        
                                @endif
                                        <p class=" red">
                                            <b>This lead belongs to @{{ lead.cre_name }}</b>
                                        </p>
                            </div>
                            </div>
                            <div class='row'>
                            

                            <div class="col-sm-6">
                            <b><i>Shipping Address</i></b>
                            <div v-show="address.id == shipping_address_id" v-for="address in lead.addresses">
                                <div>
                                    <label>@{{ address.address_type }}</label>
                                </div>
                                <div>
                                    <label>Name : </label>@{{ address.name }}
                                </div>
                                <div>
                                    <label>Address : </label>
                                    @{{ address.address }}
                                    @{{ address.city }}

                                    @{{ address.state }}
                                    @{{ address.zip }}
                                    @{{ address.country }}
                                </div>
                            </div> 
                            </div>
                            <div class="col-sm-6">
                          
                            </div>
                            </div>

                    </div></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default closer" data-dismiss="modal">Close</button>
                        <button class="btn btn-primary" v-if="status" :disabled="!shipping_address_id || !cre_id" @click="store">Add Cart</button>
                        <span class=" red" style='color: #990000' v-else>@{{message}}</span>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>


        <div class="modal fade" id="sModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 <h4 class="modal-title">Select Benefit</h4>

            </div>
            <div class="modal-body">
                
               <div class='row' style='margin: 0px 0px'>
                    <div class='col-md-12 benefitsbox'>
                           
                      <div class='row'>
                           <div v-for="benefit in benefits" class='col-md-12'>
                               <span class='radio_benefit'>
                                    <input name='benefit' type="radio" v-model="benefitSelected" value="@{{ benefit.id }}"> 
                               </span>
                               <span class='benefit_description'>
                                    @{{ benefit.description }}
                               </span>
                           </div>
                           <div v-if="referenceMessage" class='col-md-12'>
                                <p  style='color: #990000;' >@{{referenceMessage}}</p>
                           </div>
                      </div>
                      
                           </div>
                 </div>

                    <div v-show="books.length" class='row' style='margin: 0px 0px'>
                    <div class='col-md-12 booksbox'>
                           
                      <div class='row'>
                        <div class='col-md-12 '>
                        <h5>Select Book</h5>
                        </div>
                           <div v-for="book in books" class='col-md-12'>
                               <span class='radio_benefit'>
                                    <input name='book' type="radio" v-model="bookSelected" value="@{{ book.id }}"> 
                               </span>
                               <span class='benefit_description'>
                                    @{{ book.name }}
                               </span>
                           </div>
                         
                      </div>
                      </div>
                 </div>

           </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default closer" data-dismiss="modal">Close</button>
                <button @click="applyClicked($event)" type="button" class="btn btn-primary ">Apply</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

    </div>


    <template id="referenceComp">
        <tr  v-bind:class="{'refactive': reference.active, 'benefited': benefited}">
            <td>
            <a href="/lead/@{{reference.id}}/viewDispositions" target="_blank">@{{reference.id}}</a>
            </td>
            <td>
                <a v-show="reference.patient" href="/patient/@{{reference.patient.id}}/diet" target="_blank"> 
                    @{{ reference.patient.id}}
                </a>
            </td>
            <td>@{{reference.name}}</td>
            <td>@{{reference.phone}}</td>
            <td>@{{reference.email}}</td>
            <td>@{{reference.voice}}</td>
            <td>@{{reference.pivot.created_at}}</td>
            <td>@{{reference.pivot.sourced_by}}</td>
            <td>
            <input v-if="reference.patient && !benefited && roleCheck" type="checkbox" @click="getBen()" name="check[]" v-model="selected" value="@{{reference.id}}">
            <a v-show="benefit && benefit.bcart.cart_id" target='blank' href='/cart/@{{ benefit.bcart.cart_id }}' >@{{ benefit.bcart.cart_id }}</a>
            </td>
        </tr>
    </template>

<script>
    //var tab = require('vue-strap').tab;
    Vue.http.headers.common['X-CSRF-TOKEN'] = '{{ csrf_token() }}';


Vue.component('reference-row', {
    mixins: [mixin],
    template: '#referenceComp',
    props: ['reference', 'selected'],
    data: function() {
          return {
     active: false,
     loading: false,
     benefited: false,
     benefit: '',
     roleCheck: {{(Auth::user()->hasRole('cre'))?'false':'true'}}
    }
    },
    created: function () {
      this.getBenefitReference(this.reference.id);
      this.getVoice(this.reference.pivot.voice_id);
    },
    watch:{
    'selected': {
        handler:function() {
            console.log(this.selected)
        },
        deep:true
    }
},
     methods: {
          
          getBen() {
            this.active = !this.active;
             this.reference.active  =  !this.reference.active;
           },

            getVoice(vid)
          {
            this.$http.get('/getVoice/'+{{ $lead->id }}, {
                voice_id: vid
                })
                .success(function(data) {
                    //this.reference.voice = data;
                    Vue.set(this.reference, 'voice', data);
                  }.bind(this));
         },

          getBenefitReference(ref_id)
          {
            this.$http.get('/getReferenceBenefit/'+ref_id)
                .success(function(data) {

                    if(data.applied)
                    {
                        this.benefited = true;
                        this.benefit = data;
                    }
                     this.loading = false;
                  }.bind(this));
         }


         
    }

});

 Vue.transition('fade', {
  css: false,
  enter: function (el, done) {
    // element is already inserted into the DOM
    // call done when animation finishes.
    $(el)
      .css('opacity', 0)
      .animate({ opacity: 1 }, 300, done)
  },
  enterCancelled: function (el) {
    $(el).stop()
  },
  leave: function (el, done) {
    // same as enter
    $(el).animate({ opacity: 0 }, 300, done)
  },
  leaveCancelled: function (el) {
    $(el).stop()
  }
})

    var vm = new Vue({
        mixins: [mixin],
        el: '#leadreference',

        data: {
            
            loading: false,
            benefits: [],
            selected: [],
            benefitSelected: '',
            selectDescription: '',
            references: [],

            id: {{ $lead->id }},
            lead: '',
            currencies: [],
            users: [],
            status: false,
            message: '',
            cart: '',
            referenceMessage: '',
            bquantity: 0,
            bdiscount: 0,
            product_id: '',
            books: [],
            bookSelected: ''
        },

        ready: function(){
        this.findLead();
        this.getCurrencies();
        this.leadReferences();
        this.$watch('benefitSelected', function (newval, oldval) {
            if(newval==2)
                this.getBooks();
            else{
                this.books = [];
                this.bookSelected = '';
            }

            
        });
        },

        methods: {

            
        leadReferences(event) {
               
              
               this.loading = false;
               this.$http.get('/lead/{{$lead->id}}/leadReferences')
                .success(function(data) {
                    this.references = data.references;
                   

                    this.references.forEach(function(ref){
                        Vue.set(ref, 'active', false)
                        //ref.active = false;

                    });
                     this.loading = false;
                  }.bind(this));
                this.selected = [];
            },

          getBenefits: function() {
               
            
               this.loading = false;
               if(this.selected.length > 3)
                  this.referenceMessage = "Not More Than 3 References Allowed!";
               else
                   this.referenceMessage = "";
               
               this.$http.get('/getBenefits', {
                ids: this.selected
                })
                .success(function(data) {
                    this.benefits = data;
                    
                   
                    this.loading = false;
                  
                }.bind(this));
                if(this.selected.length)
                 $('#showBenefit').trigger("click");
            },
            
            getBooks: function() {
               
               this.loading = true;
               if(this.benefitSelected ==2)
                 
               this.$http.get('/categoryProducts/4')
                .success(function(data) {
                    this.books = data;
                    this.loading = false;
                  
                }.bind(this));
               
            },

            applyClicked(event)
            {
                
                for(var i=0;i<this.benefits.length;i++){
                    
                        if(this.benefits[i].id == this.benefitSelected)
                        {
                            this.bquantity = this.benefits[i].quantity;
                            this.bdiscount = this.benefits[i].discount;
                            this.product_id = this.benefits[i].product_id;
                            if(this.bookSelected)
                                this.product_id = this.bookSelected;
                            this.selectDescription = this.benefits[i].description;
                            if(this.benefits[i].product_id == null)
                                {

                                this.applyBenefit();
                               }
                            else
                                {
                                //$('#sModal').modal('hide');
                                $("#sModal button.closer").trigger("click");
                                $("#showBox").trigger("click");
                                }
                        }
                     }

            },

            applyBenefit(cart_id) {
             this.loading = true;
             this.$http.post("/applyBenefit", {
                lead_id: {{ $lead->id }},
                benefit_id: this.benefitSelected,
                benefit_description: this.selectDescription,
                cart_id: cart_id,
                reference_ids: this.selected,

                quantity: this.bquantity,
                discount: this.bdiscount,
                product_id: this.product_id
                })
             .success(function(post){
                    $("button.closer").trigger("click");
                    
                    toastr.success('Benefit Applied', 'Success!');
                      this.leadReferences();                 
                      this.loading = false;
                                         
                })
                .error(function(errors) {
                    this.loading = false;
                    this.toastErrors(errors);
                    
                })
                .bind(this);
              
            },

               findLead() {
            this.$http.get("/findLead", {
                id: this.id
            }).success(function(data){
                this.lead = data;
                this.getUsers();
                this.canCreateCart();
            }).bind(this);
        },

        getUsers() {
            this.$http.get("/api/getUsers").success(function(data){
                this.users = data;
            }).bind(this);
        },

        getCurrencies() {
            this.$http.get("/getCurrencies").success(function(data){
                this.currencies = data;
            }).bind(this);
        },

        canCreateCart() {
            this.$http.get("/canCreateReferenceCart", {
                id: this.lead.id
            })
            .success(function(data){
                if (data.status == 'true') {
                    this.status = true;
                } else {
                    
                    this.status = false;
                }    

                this.message = data.message;
                this.cart = data.cart;
            }).bind(this);
        },

        store() {
            this.$http.post("/lead/" + this.lead.id + "/cart", {
                currency_id:            1,
                cre_id:                 this.cre_id,
                source_id:              this.lead.source_id,
                shipping_address_id:    this.shipping_address_id,
                created_by:             {{ Auth::id() }}
            })
            .success(function(cart){
                this.cart = cart;
                this.applyBenefit(cart.id);
                //toastr.success("Cart created", "Success!");
                //window.location.href = "/cart/" + cart.id;
            })
            .error(function(errors){
                this.toastErrors(errors);
            })
            .bind(this);
        }
  

        },

      
})
 vm.$watch('selected', function (newval, oldval) {
        
        this.getBenefits();
    });

 
</script> 

<script type="text/javascript">
    $(document).ready(function() 
    {     
       
        $('#calendar').click(function(event) {
            event.preventDefault();
        });

        $( "#calendar" ).datetimepicker({
            timepicker: false,
            format:'d/m/Y',
            onChangeDateTime:function(dp,$input){
                $( "#datepicker" ).val($input.val())
              }
       });
        $( "#datepicker" ).datetimepicker({
            timepicker: false,
            format:'d/m/Y'
       });

        var form = $("#form");
        $('#add').click(function(event) 
        {
            event.preventDefault();
            form.find(':disabled').each(function() 
            {
                $(this).removeAttr('disabled');
            });

            $('#reference').show();
            $('#form-fields').show();
            $('#add').hide();
            $('#cancel').show();
            $('#save').show();
            $('#alert').hide();
        });
    });
</script>
<script type="text/javascript">
$(document).ready(function() 
{     
    $("#country").empty();
    $("#country").append("<option value=''> Select Country </option>");
    $.getJSON("/api/getCountryList",function(result){
        var country = "{{ $lead->country }}";
        $.each(result, function(i, field){
            if (field.country_code == country) {
                $("#country").append("<option value='" + field.country_code + "' selected> " + field.country_name + "</option>");
            }
            else
            {
                $("#country").append("<option value='" + field.country_code + "'> " + field.country_name + "</option>");
            }       
        });
        selectState(country);
    });

});

/*This function is called when country dropdown value change*/
function selectState(country_id){
    //alert(country_id);
    //$("#state").prop("disabled", true);
    $("#city").empty();
    $("#city").append("<option value=''> Select City </option>");
    //$("#city").prop("disabled", true);
    getRegionCode(country_id);
    getPhoneCode(country_id);
    //$("#state").prop("disabled", false);
}

/*This function is called when state dropdown value change*/
function selectCity(state_id){
    //$("#city").prop("disabled", true);
    getCityCode(state_id);
    //$("#city").prop("disabled", false);
}



function getPhoneCode(country_id) {
    $.getJSON("https://portal.yuwow.com/access_api/phone_code.php", { country_code: country_id }, function(result){
        $.each(result, function(i, field) {
            $("#phone_code").val('+' + field.phone_code);
        });
    });
} 

function getRegionCode(country_id) {
    var state = "{{ $lead->state?$lead->state:'' }}".toUpperCase();
    if (state == 'NEW DELHI' || state =='DELHI' || state =='GURGAON' || state =='FARIDABAD' || state =='GHAZIABAD'  || state =='NOIDA') {
        state = "IN.07";
    }
    $.getJSON("/api/getRegionList", { country_code: country_id }, function(result){
        $("#state").empty();
        $("#state").append("<option value=''> Select State </option>");
        $.each(result, function(i, field) {            
            if (field.region_code == state) {
                $("#state").append("<option value='" + field.region_code + "' selected> " + field.region_name + "</option>");
            }
            else
            {
                $("#state").append("<option value='" + field.region_code + "'> " + field.region_name + "</option>");
            }
        });
    });
    getCityCode(state);    
}

function getCityCode(state_id) {

    var city = "{{ $lead->city?$lead->city:'' }}";
    $.getJSON("/api/getCityList", { region_code: state_id }, function(result){
        $("#city").empty();
        $("#city").append("<option value=''> Select City </option>");
        $.each(result, function(i, field) {           
            if (field.city_name.toUpperCase() == city.toUpperCase()) {
                $("#city").append("<option value='" + field.city_name + "' selected> " + field.city_name + "</option>");
            }
            else
            {
                $("#city").append("<option value='" + field.city_name + "'> " + field.city_name + "</option>");
            }
        });
    });
    
}
//Fetch Lead Voices   
    $("#voice").append("<option value=''> Select Voice </option>");
    $.getJSON("/api/getVoiceList",function(result){
        $.each(result, function(i, field){
            $("#voice").append("<option value='" + field.id + "'> " + field.name + "</option>");
        });
    });
</script>
<script type="text/javascript" src="/js/form.js"></script>  
@endsection

<style>
.radio_benefit
{
    display: inline-block;
    margin-right: 10px;
}
.benefit_description
{
    display: inline-block;
}
.benefitsbox
{

    padding: 10px;
    border: 1px solid #d1d1d1;
    border-radius: 10px;
    background: #e6f5ff;

}
.booksbox
{
    padding: 10px;
    border: 1px solid #bbb;
    border-radius: 2px;
    background: #ffffff;
    margin-top: 20px;
}
.benefitapply
{
    margin-top: 10px;
}
tr
{
transition: all 0.5s ease;

}
tr.refactive
{

    background: #b3e0ff;
}

.expand-transition {
  transition: all .1s ease;
  height: 100px;
  padding: 0px 15px;
  background-color: #fff;
  overflow: hidden;
}

/* .expand-enter defines the starting state for entering */
/* .expand-leave defines the ending state for leaving */
.expand-enter, .expand-leave {
  height: 0;
  padding: 0 10px;
  opacity: 0;
}

.modal-header, .modal-footer
{
    background: #e8e8e8;
}
tr.benefited
{

    background: #ffe6e6;
   
}
</style>