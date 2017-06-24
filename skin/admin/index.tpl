<?php
/**
 * CubeCart v6
 * ========================================
 * CubeCart is a registered trade mark of CubeCart Limited
 * Copyright CubeCart Limited 2014. All rights reserved.
 * UK Private Limited Company No. 5323904
 * ========================================
 * Web:   http://www.cubecart.com
 * Email:  salescubecart.com
 * License:  GPL-3.0 http://opensource.org/licenses/GPL-3.0
 */
?>
<form action="{$VAL_SELF}" method="post" enctype="multipart/form-data">
	<div id="Billplz" class="tab_content">
  		<h3>{$TITLE}</h3>
  		<p>{$LANG.billplz.module_description}</p>
  		<fieldset><legend>{$LANG.module.cubecart_settings}</legend>
			<div><label for="status">{$LANG.common.status}</label><span><input type="hidden" name="module[status]" id="status" class="toggle" value="{$MODULE.status}" /></span></div>
			<div><label for="position">{$LANG.module.position}</label><span><input type="text" name="module[position]" id="position" class="textbox number" value="{$MODULE.position}" /></span></div>
			<div>
				<label for="scope">{$LANG.module.scope}</label>
				<span>
					<select name="module[scope]">
      						<option value="both" {$SELECT_scope_both}>{$LANG.module.both}</option>
      						<option value="main" {$SELECT_scope_main}>{$LANG.module.main}</option>
      						<option value="mobile" {$SELECT_scope_mobile}>{$LANG.module.mobile}</option>
    					</select>
				</span>
			</div>
			<div><label for="default">{$LANG.common.default}</label><span><input type="hidden" name="module[default]" id="default" class="toggle" value="{$MODULE.default}" /></span></div>
			<div><label for="description">{$LANG.common.description} *</label><span><input name="module[desc]" id="description" class="textbox" type="text" value="{$MODULE.desc}" /></span></div>
                        <div><label for="api_key">{$LANG.billplz.api_key}</label><span><input name="module[api_key]" id="api_key" class="textbox" type="text" value="{$MODULE.api_key}" required="1" /></span></div>
			<div><label for="x_signature">{$LANG.billplz.x_signature}</label><span><input name="module[x_signature]" id="x_signature" class="textbox" type="text" value="{$MODULE.x_signature}" required="1" /></span></div>
			<div><label for="collection_id">{$LANG.billplz.collection_id}</label><span><input name="module[collection_id]" id="collection_id" class="textbox" type="text" value="{$MODULE.collection_id}" /></span></div>
			<div><label for="billplz_description">{$LANG.billplz.billplz_description}</label><span><input name="module[billplz_description]" id="billplz_description" class="textbox" type="text" value="{$MODULE.billplz_description}" /></span></div>
			<div>
				<label for="api_key">{$LANG.billplz.send_bill}</label>
					<span>
						<select name="module[send_bill]">
        					<option value="0" {$SELECT_send_bill_0}>{$LANG.billplz.mode_0}</option>
        					<option value="1" {$SELECT_send_bill_1}>{$LANG.billplz.mode_1}</option>
        					<option value="1" {$SELECT_send_bill_2}>{$LANG.billplz.mode_2}</option>
        					<option value="1" {$SELECT_send_bill_3}>{$LANG.billplz.mode_3}</option>
    					</select>
    				</span>
    			</div>
            </fieldset>
            <p>{$LANG.module.description_options}</p>
  		</div>
  		{$MODULE_ZONES}
  		<div class="form_control">
			<input type="submit" name="save" value="{$LANG.common.save}" />
  		</div>
  	
  	<input type="hidden" name="token" value="{$SESSION_TOKEN}" />
</form>
<hr>