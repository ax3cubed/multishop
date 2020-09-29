<form id="contact_form">

    <h2><?php echo t('Contact us');?></h2>

    <div class="success-message">
        <!--- display successful message when sent ok -->
        <?php $form->successMessage(); ?>
    </div>    
    
    <div class="error-message">
        <!--- display error messages if any -->
        <?php $form->errorMessage(); ?>
    </div>    

    <div class="form-content">
        
        <p>
            <label><?php $form->label('name');?></label>
            <input type="text" name="ContactForm[name]" id="ContactForm_name" value="<?php $form->value('name');?>" placeholder="<?php $form->placeholder('name');?>">
        </p>

        <p>
            <label><?php $form->label('email');?></label>
            <input type="text" name="ContactForm[email]" id="ContactForm_email" value="<?php $form->value('email');?>" placeholder="<?php $form->placeholder('email');?>">        
        </p>

        <p>
            <label><?php $form->label('body');?></label>
            <textarea name="ContactForm[body]" id="ContactForm_body" placeholder="<?php $form->placeholder('body');?>"><?php $form->value('body');?></textarea>
        </p>

        <p>
            <input type="button" id="contact_form_send" value="<?php echo t('Send');?>" />
        </p>

    </div>    

</form>
