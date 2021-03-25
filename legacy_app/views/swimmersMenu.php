<?php $access = $_SESSION['TENANT-' . nezamy_app()->tenant->getId()]['AccessLevel'];
if (isset($_SESSION['TENANT-' . nezamy_app()->tenant->getId()]['Swimmers-FamilyMode']) && $_SESSION['TENANT-' . nezamy_app()->tenant->getId()]['AccessLevel'] != "Parent") { ?>
<div class="bg-secondary text-white shadow mb-3" style="margin-top:-1rem;">
  <div class="<?=$container_class?>">
    <nav class="nav nav-underline py-2">
      <p class="mb-0">
        You're in family mode. <strong><a class="text-white" href="<?php echo
        autoUrl("swimmers/family/exit")?>">Exit family mode</a></strong> before
        adding swimmers that don't belong to the current family.
      </p>
    </nav>
  </div>
</div>
<?php } ?>