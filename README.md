# Curated Group Join

Curated Group Join (com.pawpawworks.civicrm.curatedgroupjoin) is an extension for
[CiviCRM](https://civicrm.org) which enables site admins to add to a form a
curated list of groups. While CiviCRM offers a "groups" profile field which can
be used to add group sign-up to most forms, the list cannot be curated; all
public groups are displayed.

Potential uses for this extension:

* On a membership sign-up form, allow members to select the member benefits to
  which they would like to opt-in. (Assumes opt-in is represented as group
  membership.)
* Allow users to sign up for email communications, or to join an interest group,
  without overwhelming them with the list of all public groups.

## Installation

This extension has not yet been published for in-app installation. [General
extension installation instructions](https://docs.civicrm.org/sysadmin/en/latest/customize/extensions/#installing-a-new-extension)
are available in the CiviCRM System Administrator Guide.

## Requirements

* PHP v5.6+
* CiviCRM v4.7+

## Usage

1. Go to *Contribution > Manage Contribution Pages*.
2. For the contribution form of interest, select *Configure > Include Profiles*.
3. Supply a label and select the groups to display to end users. Only static
   (vs. smart) groups will be displayed, and only if they are active, not hidden,
   and have their visibility set to "Public Pages."
   ![Screenshot: administrative user interface](/images/config.png)
4. Visit the public contribution page and note that users can select each group
   as a checkbox.
   ![Screenshot: membership form](/images/membership-form.png)

## Technical Details

This extension creates a new database table `civicrm_curated_group_join` in
which configurations are stored.

Existing CiviCRM forms are modified using classes which implement an interface
`Civi\Curatedgroupjoin\FormMod\IFace`. The classes are named for the forms that
they modify. See
[`Civi\Curatedgroupjoin\FormMod\CRM\Contribute\Form\ContributionPage\Custom`][exBackend]
for an example of how to add a configuration screen for site administrators.
See [`Civi\Curatedgroupjoin\FormMod\CRM\Contribute\Form\Contribution\Main`][exFrontend]
for an example of exposing the configured groups to end users. Note that new
form-modifying classes can be added to a third-party extension provided they are
named according to the above pattern and that CiviCRM's autoloader can find them.

## Known Issues/Limitations

* When a contribution form is configured not to use a confirmation page, and the
  user submitting the form is anonymous, CiviCRM does not provide
  `hook_civicrm_postProcess` subscribers a way to identify the contact.
  Therefore, contacts cannot be added to groups in this context; as a stopgap
  measure, the extension will not activate. ([#1][i1])
* Supports only Contribution Pages. There are no plans to develop similar
  functionality for event registration pages or other forms. If you would like
  to develop or sponsor such functionality, please reach out.

## License

[AGPL-3.0](/LICENSE.txt)

[exBackend]: /Civi/Curatedgroupjoin/FormMod/CRM/Contribute/Form/ContributionPage/Custom.php
[exFrontend]: /Civi/Curatedgroupjoin/FormMod/CRM/Contribute/Form/Contribution/Main.php
[i1]: https://github.com/pawpawworks/com.pawpawworks.civicrm.curatedgroupjoin/issues/1
