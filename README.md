# Members Only Events

## Overview
This extension will add a check box -> "Is members only event" and a Drupal permission "Can register for Members only events". If the "Is members only event" tick box is ticked, only a user with the "Can register for Members only events" can register for the Civi CRM Event. CiviCRM Member Role Sync module can be used to dynamically grant this permission to users who has active memberships.

An duration check option is added in Administer menu to enable and disable the validation of membership duration.

Online registration needs to be enabled, when creating the Event in Civi CRM.

## Example
Configuration for a typical use case:
1. Install the members only extension on CiviCRM side
2. Enable CiviCRM member role sync module on Drupal side
3. Create a "member" Drupal role and grant "Can register for members only event" permission.
4. In Drupal member role sync configuration, add a rule for each membership type needed to add "member" role when membership has status "New", "Current" and "Grace". Remove "member" role when membership is with any other status.
5. All users who login to the site will get/ lose the "member" role according to the rule in step 4.
6. If a CiviEvent is configured to be "members only event", then users with the member role will be able to register without a problem.
7. If a CiviEvent is configured to be "members only event", then users without the member role will be asked to sign up for membership first. The sign up button should take user to the url specified in the CiviEvent members only event tab.
