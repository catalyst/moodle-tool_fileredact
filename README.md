<p align="center">
  <img src="https://user-images.githubusercontent.com/9924643/201838789-d96b3174-49e7-4347-80f6-6782c4933e89.png" alt="file redact tool">
</p>

<!-- Uncomment when ready -->
![GitHub Workflow Status (branch)](https://img.shields.io/github/workflow/status/catalyst/moodle-tool_fileredact/ci/alpha)

* [What is this?](#what-is-this)
* [Branches](#branches)
* [Installation](#installation)
* [Support](#support)

## What is this?

File redact is a tool which processes files and strips various content from them before they are persisted and used.

## Branches

| Moodle version    | Branch           | PHP       |
|-------------------|------------------|-----------|
| TBA               | TBA              | TBA       |

## Dependencies

For the following settings, you will need to install their associated dependencies before you can use them.

| Setting  | Details
|-------------------|------------------|-----------|
| jpgstripexifenabled | Remove EXIF from JPEG images. <br><small>Requires **exiftool** installed</small>
| pdflattenenabled | Remove JS, Actions, etc from PDF by flattening it. <br><small>Requires **ghostscript (gs)** installed</small> <br><small>Requires **strings** installed</small>

## Installation

From Moodle siteroot:

```
git clone git@github.com:catalyst/moodle-tool_fileredact.git admin/tool/fileredact
```

## Support

If you have issues please log them in
[GitHub](https://github.com/catalyst/moodle-tool_fileredact/issues).

Please note our time is limited, so if you need urgent support or want to
sponsor a new feature then please contact
[Catalyst IT Australia](https://www.catalyst-au.net/contact-us).


## Crafted by Catalyst IT
This plugin was developed by [Catalyst IT Australia](https://www.catalyst-au.net/).

<img alt="Catalyst IT" src="https://cdn.rawgit.com/CatalystIT-AU/moodle-auth_saml2/MOODLE_39_STABLE/pix/catalyst-logo.svg" width="400">
