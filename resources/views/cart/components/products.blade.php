<div class="modal-mask" v-show="showProductModal" transition="modal">
    <div class="modal-wrapper">
        <div class="modal-container">
            <div class="modal-header row">
                <div class="col-md-2">
                    <h4>Add Product</h4>
                </div>
                <div class="col-md-8">
                    <li v-for="product in products">
                        @{{ product.name }}
                    </li>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-primary" @click="storeProducts">
                        <i class="fa fa-save"></i>
                    </button>
                    <button type="button" class="btn btn-danger" @click="showProductModal=false">
                        <i class="fa fa-close"></i>
                    </button>
                </div>
            </div>    
            <div class="modal-body">
                <div class="row">
                    <div class="box box-default">
                        <div class="box-body pre-scrollable">
                            <div v-for="category in categories">
                                <h3 style="text-align:center">@{{ category.name }}</h3>

                                <div class="col-md-12"  style="padding:5px;border-bottom: 1px solid silver; font-weight: 500; color: #fff; font-size: 14px; background-color: #797979;">
                                    <div class="col-md-2">Name</div>
                                    <div class="col-md-3">Description</div>
                                    <div class="col-md-1">Quantity</div>
                                    <div class="col-md-1">Price</div>
                                    <div class="col-md-1">Amount</div>
                                    <div class="col-md-3">FREE Offers</div>
                                </div>

                                <div v-for="pdt in category.products" class="@{{ pdt|exists }} col-md-12" style="padding:5px;border-bottom: 1px solid silver">
                                    <product
                                        :product.sync="pdt"
                                        :cart.sync="cart"
                                    > 
                                    </product>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
.true{background-color: #4caf50;}
</style>