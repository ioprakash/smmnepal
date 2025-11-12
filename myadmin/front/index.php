<?php include 'header.php'; ?>
<style>
html,body
{
    width: 100%;
    height: 100%;
    margin: 0px;
    padding: 0px;
    overflow-x: hidden; 
}
.pt-4 {
    padding-top: 1.5rem !important;
}
.px-4 {
    padding-right: 1.5rem !important;
    padding-left: 1.5rem !important;
}
.g-4, .gy-4 {
    --bs-gutter-y: 1.5rem;
}
.g-4, .gx-4 {
    --bs-gutter-x: 1.5rem;
}
.rounded {
    border-radius: 5px !important;
}

.p-4 {
    padding: 1.5rem !important;
}
.align-items-center {
    align-items: center !important;
}
.justify-content-between {
    justify-content: space-between !important;
}
.d-flex {
    display: flex !important;
}
.text-primary {
    color: #EB1616 !important;
}
.ms-3 {
    margin-left: 1rem !important;
}
.mb-2 {
    margin-bottom: .5rem !important;
}
.mb-0 {
    margin-bottom: 0 !important;
}
.satistics {
    margin-bottom:10px;
  

}
.satistics  {
    margin:5px;
    border-radius:10px;
    box-shadow: 0 .46875rem 2.1875rem rgba(4,9,20,.03),0 .9375rem 1.40625rem rgba(4,9,20,.03),0 .25rem .53125rem rgba(4,9,20,.05),0 .125rem .1875rem rgba(4,9,20,.03);
    transition: all .2s;
}

.button-1 {
  background-color: #EA4C89;
  border-radius: 5px;
  border-style: none;
  box-sizing: border-box;
  color: #FFFFFF;
  cursor: pointer;
  font-family: "Haas Grot Text R Web", "Helvetica Neue", Helvetica, Arial, sans-serif;
  font-size: 14px;
  font-weight: 500;
  height: 40px;
  line-height: 20px;
  margin: 8px;
  outline: none;
  padding: 10px 16px;
  position: relative;
  text-align: center;
  text-decoration: none;
}
body.dark-mode .button-1 {
    color:#fff;
}


</style>
<div class="container-fluid">
<div class=" row">
    
<div class="container-fluid pt-4 px-4">
<div class="row g-4">
    <div style="border:2px solid #7b75d9;" class="satistics col-sm-6 col-xl-3">
<div class="rounded d-flex align-items-center justify-content-between p-4">
<img width="60" height="60" src="img/admin/people.png">
    <div class="ms-3">
<p class="mb-2">Total Users</p>
<h5 class="mb-0 text-right"><?php echo countRow(["table"=>"clients"]);?></h5>
    </div>
</div>
    </div>
    <div style="border:2px solid #0796fe;" class="satistics col-sm-6 col-xl-3">
<div class="rounded d-flex align-items-center justify-content-between p-4">
<img width="60" height="60" src="img/admin/orders.png">
    <div class="ms-3">
<p class="mb-2">Total Orders</p>
<h5 class="mb-0 text-right"><?php echo $settings["panel_orders"]; ?></h5>
    </div>
</div>
    </div>
    <div style="border:2px solid #fa4957;" class="satistics col-sm-6 col-xl-3">
<div class="rounded d-flex align-items-center justify-content-between p-4">
<img width="60" height="60" src="img/admin/failedorders.png">
    <div class="ms-3">
<p class="mb-2">Failed Orders</p>
<h5 class="mb-0 text-right"><?php echo $failCount;?></h5>
    </div>
</div>
    </div>
    <div style="border:2px solid #40d3a2;" class="satistics col-sm-6 col-xl-3">
<div class="rounded d-flex align-items-center justify-content-between p-4">
<img width="60" height="60" src="img/admin/payments.png">
    <div class="ms-3">
<p class="mb-2">Payments</p>
<h5 class="mb-0 text-right"><?php echo countRow(["table"=>"payments"]);?></h5>
    </div>
</div>
    </div>
</div>
    </div>
 
<div style="border:2px solid #2B2D42;border-radius:10px;box-shadow: rgba(0, 0, 0, 0.15) 1.95px 1.95px 2.6px;margin:5px;">
<h4 style="padding:8px;border-bottom:2px solid #2B2D42;padding-bottom:15px;" class="text-left">
Page Shortcuts
</h4>
<div>
<a style="background-color:#07BEB8" class="button-1" href="/admin/clients">Manage Users</a>
<a style="background-color:#FF6D00" class="button-1" href="/admin/orders">Manage Orders</a>
<a style="background-color:#FF5D8F" class="button-1" href="/admin/tasks">Refill and Cancel Tasks</a>
<a style="background-color:#0C0A3E" class="button-1" href="/admin/broadcasts">Manage Broadcasts</a>
<a style="background-color:#FFC300" class="button-1" href="/admin/settings/site_count">Manage Fake Orders</a>
<a style="background-color:#F9564F" class="button-1" href="/admin/settings/currency-manager">Manage Currencies</a>
<a style="background-color:#44AF69" class="button-1" href="/admin/settings/providers">Manage Sellers</a>
<a style="background-color:#00B4D8" class="button-1" href="/admin/appearance">Manage Themes</a>
<a style="background-color:#003566" class="button-1" href="/admin/settings/payment-methods">Payment Methods</a>

</div>
</div>


</div>
</div>

<div class="modal modal-center fade" id="confirmChange" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">
   <div class="modal-dialog modal-dialog-center" role="document">
      <div class="modal-content">
 <div class="modal-body text-center">
    <h4>Are you sure you want to proceed ?</h4>
    <div align="center">
       <a class="btn btn-primary" href="" id="confirmYes">Yes</a>
       <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
    </div>
 </div>
      </div>
   </div>
</div>
<?php include 'footer.php'; ?>
