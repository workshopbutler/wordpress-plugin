Below you will find a step-by-step release process for the plugin

## Steps

1. Add the description of the new version to *workshop-butler/readme.txt*. The description of the update is available
to end customers when they update the plugin so make sure it's understandable for them.
2. Update the version in *workshop-butler/workshop-butler.php* in two places:
    - *Version* attribute in the top comment. It's used by WordPress release system.
    - *WSB_INTEGRATION_VERSION* constant used by the plugin to identify the plugin update.
3. Create a release in GitHub with the number identical to the version in steps 1 and 2.
4. After publishing the release, GitHub action is fired to publish a new version to WordPress repository.
5. Celebrate!
