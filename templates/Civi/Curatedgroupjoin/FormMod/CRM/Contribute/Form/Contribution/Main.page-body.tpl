<fieldset class="cgj-wrapper">
  <legend>{$cgj_legend}</legend>
  <div class="crm-section cgj-section">
    <div class="content">{$form.cgj_groups.html}</div>
    <div class="clear"></div>
  </div>
</fieldset>

<script type="text/javascript">
  {literal}
    CRM.$(function ($) {
      $('.cgj-wrapper').insertBefore('#crm-submit-buttons');
    });
  {/literal}
</script>