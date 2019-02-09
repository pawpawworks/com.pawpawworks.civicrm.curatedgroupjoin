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
      // insert groups form elements in form body
      $('.cgj-wrapper').insertBefore('#crm-submit-buttons');

      // pre-populate groups when filling out form on behalf of another contact
      var contactSelector = $('#select_contact_id');
      contactSelector.on('change', function() {
        const contactId = $(this).val();

        CRM.api3('GroupContact', 'get', {
          contact_id: contactId,
          options: {
            limit: 0
          },
          sequential: 1,
          status: 'Added'
        }).then(function (result) {
          $('[id^=cgj_groups_]').prop('checked', false);
          result.values.forEach(function (item) {
            $(`#cgj_groups_${item.group_id}`).prop('checked', true);
          });
        });
      });
    });
  {/literal}
</script>