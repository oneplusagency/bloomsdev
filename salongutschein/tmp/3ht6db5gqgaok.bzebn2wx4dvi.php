<?php echo $this->render('layout/header.html',NULL,get_defined_vars(),0); ?>
<?php if ($view): ?>
    
        <?php echo $this->render($this->raw($view),NULL,get_defined_vars(),0); ?>
    
<?php endif; ?>
<?php echo $this->render('layout/footer.html',NULL,get_defined_vars(),0); ?>