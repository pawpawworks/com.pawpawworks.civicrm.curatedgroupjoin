{crmScope extensionKey='com.pawpawworks.civicrm.curatedgroupjoin'}

<div class="crm-accordion-wrapper crm-cgj-settings-accordion">
  <div class="crm-accordion-header">{ts}Group Settings{/ts}</div>
  <div class="crm-accordion-body">
    <div class="help">
      <p>{ts}The settings below are provided by the <b>Curated Group Join</b> extension. Use them to offer contributors a curated list of groups they may join.{/ts}</p>
      <p><i>{ts}Note: A similar effect can be achieved with CiviCRM's "groups" profile field. However, instead of displaying all public groups, this extension allows you to specify which groups should appear.{/ts}</i></p>
      <p>{ts}Example uses:{/ts}</p>
      <ul>
        <li>{ts}Allow members to opt-in to different groups which represent member benefits they may be entitled to.{/ts}</li>
        <li>{ts}Display a subset of your public groups to allow users to sign up for email communications or to join an interest group.{/ts}</li>
      </ul>
    </div>
    <table class="form-layout-compressed">
      <tr class="crm-contribution-contributionpage-custom-form-block-cgj_label">
        <td class="label">{$form.cgj_label.label}</td>
        <td class="html-adjust">{$form.cgj_label.html}
          <div class="description">{ts}Provide a label for the list of groups that will be displayed to end users{/ts}</div>
        </td>
      </tr>
      <tr class="crm-contribution-contributionpage-custom-form-block-cgj_groups">
        <td class="label">{$form.cgj_groups.label}</td>
        <td class="html-adjust">{$form.cgj_groups.html}
          <div class="description">{ts}The list of groups from which end users will be able to select{/ts}</div>
        </td>
      </tr>
    </table>
  </div>
</div>

{/crmScope}