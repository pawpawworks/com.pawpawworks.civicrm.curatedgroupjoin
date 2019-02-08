<div class="crm-section cgj-section">
  <div class="label">{$form.cgj_groups.label}</div>
  <div class="content">{$form.cgj_groups.html}</div>
  <div class="clear"></div>
</div>

<script type="text/javascript">
  {literal}
    CRM.$(function ($) {
      $('.cgj-section').insertBefore('#crm-submit-buttons');
    });
  {/literal}
</script>