<div class="crm-group cgj-section">
  <div class="header-dark">
    {$cgj_label}
  </div>
  <div class="crm-section no-label">
    {foreach from=$cgj_selected_groups item=group_title}
      <div class="content">{$group_title}</div>
    {/foreach}
    <div class="clear"></div>
  </div>
</div>

<script type="text/javascript">
  {literal}
    CRM.$(function ($) {
      $('.cgj-section').insertBefore('.continue_instructions-section');
    });
  {/literal}
</script>