

<?php
$body->appendInnerHTML('<div class="wrapper"><div class="content">
        <div class="container-fluid">
          <div class="row">
            
            <div class="col-md-3 m-auto p-auto">
              <div class="card ">
                <div class="card-header card-header-rose card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">contacts</i>
                  </div>
                  <h4 class="card-title">Ingreso</h4>
                </div>
                <div class="card-body ">
                  <!--<form class="form-horizontal" id="formLogin">-->
                    <div class="row">
                      <label class="col-md-3 col-form-label">Email</label>
                      <div class="col-md-9">
                        <div class="form-group has-default bmd-form-group">
                          <input id="email" name="email" type="email" class="form-control">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <label class="col-md-3 col-form-label">Password</label>
                      <div class="col-md-9">
                        <div class="form-group bmd-form-group">
                          <input id="password" name="password" type="password" class="form-control">
                        </div>
                      </div>
                    </div>
                    <div class="row"><button type="button" id="submitLogin" class="btn btn-fill btn-rose m-auto">Sign in<div class="ripple-container"></div></button></div>
                  <!--</form>-->
                  
                </div>
              </div>
            </div>
            
            
          </div>
        </div>
      </div></div>');
?>