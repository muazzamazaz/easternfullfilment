<div class="row">
    <!-- Basic Information -->
    <div class="col-md-4">
        <div class="form-group">
            <label>{{trans('Item Name')}} *</label>
            <input type="text" name="item_name" class="form-control" id="item_name" aria-describedby="item_name" required>
            <span class="validation-msg" id="item_name-error"></span>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>{{trans('Inventory ID')}}</label>
            <input type="text" name="inventory_id" class="form-control" id="inventory_id" aria-describedby="inventory_id">
            <span class="validation-msg" id="inventory_id-error"></span>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>{{trans('Platform Source')}}</label>
            <input type="text" name="platform_source" class="form-control" id="platform_source" aria-describedby="platform_source">
            <span class="validation-msg" id="platform_source-error"></span>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>{{trans('SKU')}}</label>
            <input type="text" name="sku" class="form-control" id="sku" aria-describedby="sku">
            <span class="validation-msg" id="sku-error"></span>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>{{trans('Store SKU')}}</label>
            <input type="text" name="store_sku" class="form-control" id="store_sku" aria-describedby="store_sku">
            <span class="validation-msg" id="store_sku-error"></span>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>{{trans('Bar Code')}}</label>
            <input type="text" name="bar_code" class="form-control" id="bar_code" aria-describedby="bar_code">
            <span class="validation-msg" id="bar_code-error"></span>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>{{trans('Re-Order Point')}}</label>
            <input type="number" name="reorder_point" class="form-control" id="reorder_point" aria-describedby="reorder_point" min="0">
            <span class="validation-msg" id="reorder_point-error"></span>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>{{trans('Dimensions (in inches)')}}</label>
            <div class="row">
                <div class="col-md-4">
                    <input type="number" name="length" class="form-control" id="length" aria-describedby="length" min="0">
                    <label>Length</label>
                </div>
                <div class="col-md-4">
                    <input type="number" name="width" class="form-control" id="width" aria-describedby="width" min="0">
                    <label>Width</label>
                </div>
                <div class="col-md-4">
                    <input type="number" name="height" class="form-control" id="height" aria-describedby="height" min="0">
                    <label>Height</label>
                </div>
            </div>
            <span class="validation-msg" id="dimensions-error"></span>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>{{trans('Weights')}}</label>
            <div class="row">
                <div class="col-md-6">
                    <input type="number" name="weight_lbs" class="form-control" id="weight_lbs" aria-describedby="weight_lbs" min="0">
                    <label>lbs</label>
                </div>
                <div class="col-md-6">
                    <input type="number" name="weight_oz" class="form-control" id="weight_oz" aria-describedby="weight_oz" min="0">
                    <label>oz</label>
                </div>
            </div>
            <span class="validation-msg" id="weights-error"></span>
        </div>
    </div>
</div>

<div class="row">
    <!-- Status -->
    <div class="col-md-4">
        <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-control" id="status">
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>
            <small><strong>Heads up!</strong> We will ship your active items. Inactive items will be ignored.</small>
        </div>
    </div>
    
    
        <div class="col-md-4">
        <div class="form-group">
            <label>Tariff Code</label>
            <input type="text" name="tariff_code" class="form-control" id="tariff_code" aria-describedby="tariff_code">
            <span class="validation-msg" id="tariff_code-error"></span>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>HS Code Lookup</label>
            <input type="text" name="hs_code_lookup" class="form-control" id="hs_code_lookup" aria-describedby="hs_code_lookup">
            <span class="validation-msg" id="hs_code_lookup-error"></span>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Tariff Code Format</label>
            <input type="text" name="tariff_code_format" class="form-control" id="tariff_code_format" aria-describedby="tariff_code_format">
            <span class="validation-msg" id="tariff_code_format-error"></span>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="form-group">
            <label>Value</label>
            <input type="number" name="value" class="form-control" id="value" aria-describedby="value">
            <span class="validation-msg" id="value-error"></span>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="form-group">
            <label>Select one item attribute (if applicable):</label>
            <select name="item_attribute" class="form-control" id="item_attribute">
            <option value="No Requirements">No Requirements</option>
            <option value="Fragile">This item is fragile. This option will increase the item's size to account for protective wrap which may lead to increased fulfillment charges</option>
            <option value="Foldable">This item is foldable</option>
            <option value="Clothing">This item is clothing/apparel</option>
            <option value="Other">Other</option>
            <option value="Book">This item is a book</option>
            <option value="Custom Package">This item is a custom package</option>
            <option value="Box">Box</option>
            <option value="Custom Dunnage">This item is custom dunnage</option>
            <option value="Marketing Insert">This item is a marketing insert</option>
            <option value="Poster">This item is a poster</option>
            <option value="Prop 65 Label">This item is a Prop 65 label</option>
            </select>
            <small><strong>Heads up!</strong> Please note this is a guide, not a guarantee. Final box type will depend on various factors.</small>
        </div>
    </div>
    
        <div class="col-md-6">
        <div class="form-group">
            <label>Select packaging type based on requirements:</label>
            <select name="packaging_type" class="form-control" id="packaging_type">
            <option value="Box">Box</option>
            <option value="Bubble Mailer">Bubble Mailer</option>
            <option value="Poly Mailer">Poly Mailer</option>
            <option value="Custom Box">Custom Box</option>
            <option value="Custom Bubble Mailer">Custom Bubble Mailer</option>
            <option value="Custom Poly Mailer">Custom Poly Mailer</option>
            <option value="Ship in Own Container">Ship in Own Container</option>
            </select>
        </div>
    </div>
    
</div>


<div class="return-preferences">
    <!-- Return Action -->
    <div class="return-action">
        <label>Return Action</label>
        <select name="return_action" class="form-control">
            <option value="Restock">Restock</option>
            <option value="Dispose">Dispose</option>
        </select>
    </div>

    <!-- Backup Action -->
    <div class="backup-action">
        <label>Backup Action</label>
        <select name="backup_action" class="form-control">
            <option value="Restock">Restock</option>
            <option value="Dispose">Dispose</option>
        </select>
    </div>

    <!-- Instructions -->
    <div class="instructions">
        <label>Instructions</label>
        <textarea name="return_instructions" class="form-control" rows="3" maxlength="200"></textarea>
        <span class="char-count">0 / 200</span>
    </div>

    <!-- Return To Sender Action -->
    <div class="return-to-sender-action">
        <label>Return To Sender Action</label>
        <select name="return_to_sender_action" class="form-control">
            <option value="Restock">Restock</option>
            <option value="Dispose">Dispose</option>
        </select>
    </div>

    <!-- Return To Sender Backup Action -->
    <div class="return-to-sender-backup-action">
        <label>Return To Sender Backup Action</label>
        <select name="return_to_sender_backup_action" class="form-control">
            <option value="Restock">Restock</option>
            <option value="Dispose">Dispose</option>
        </select>
    </div>
</div>

<div class="row">
    <!-- Customs Information -->


    <div class="col-md-4">
        <div class="form-group">
            <label>Value</label>
            <div class="input-group">
                <span class="input-group-addon">$</span>
                <input type="number" name="value" class="form-control" id="value" aria-describedby="value" min="0">
            </div>
            <span class="validation-msg" id="value-error"></span>
        </div>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control" id="description" rows="2" maxlength="150"></textarea>
            <span class="char-count" id="description-char-count">0 / 150</span>
            <span class="validation-msg" id="description-error"></span>
        </div>
    </div>

    <!-- Heads up! Message -->
    <div class="col-md-12">
        <div class="alert alert-info">
            <strong>Heads up!</strong> This information ensures that we fulfill your packages accurately internationally. Please note that packages will be held at customs if proper information is not filled in.
        </div>
    </div>
</div>


