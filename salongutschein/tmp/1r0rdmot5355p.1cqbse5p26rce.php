<article class="container p-md-0 px-2 mt-5" id="page-preise">
    <section style="background-color: #fff;padding: 20px;width: 100%;">

        <?php foreach (($categories?:[]) as $KEY=>$category): ?>

            <table id="1-tbprice" class="table table-borderless table-price">
                <thead>
                    <tr>
                        <th colspan="2" scope="col-m">
                            <h3><?= ($category['name']) ?></h3>
                        </th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach (($category['items']?:[]) as $KEY2=>$item): ?>
                        <tr>
                            <td scope="row" class="w-75 groshi-name"><?= ($item['title'])."
" ?>
                                <?= ($createExtraInfoLabel(@$item))."
" ?>
                            </td>
                            <td class="td-gold groshi-price"><?= ($item['price']) ?> <span class="currency-symbol">€</span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endforeach; ?>

        <div class="card_payment_wrp">
            <img src="./assets/images/Piktogram-Kreditkarte.png" alt="Piktogram-Kreditkarte" class="img-fluid">
            <p>Bitte beachten: In der Akademie ist nur Kartenzahlung möglich!</p>
        </div>
    </section>
</article>

<style>
    @media (min-width: 768px) {
        #page-preise section {
            margin: 0 200px;
        }
    }
</style>