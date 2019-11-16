
<!-- Tab panes -->
<div class="tab-content">
    <!-- Home tab content -->
    <div class="tab-pane active" id="control-sidebar-settings-tab">
        <h3 class="control-sidebar-heading">General Settings</h3>
        <!-- /.form-group -->
        <div class="form-group">
            <label class="control-sidebar-subheading">
                Default Date
                <div style="width: 50%;" class="pull-right">
                    <input type="text" class="form-control decimal_number_only datepicker_reg" name="default_date" id="rsb_default_date" placeholder="Default date">
                </div>
            </label>
            <p>
                Default date for form auto filling
            </p>
        </div>
        <!-- /.form-group -->
        <div class="form-group">
            <label class="control-sidebar-subheading">
                Driver Auto Selection
                <input type="checkbox" id="rsb_driver_auto_selection" value="1" name="driver_auto_selection" class="pull-right" {{ !empty($settings->driver_auto_selection) ? "checked" : "" }}>
            </label>
            <p>
                Select driver based on last transportation
            </p>
        </div><br>
    </div>
    <!-- /.tab-pane -->
</div>
