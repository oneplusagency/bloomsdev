<div class="row">
    <div class="col-md-12">
        <?php echo $this->render('layout/blooms-logo.html',NULL,get_defined_vars(),0); ?>

        <nav class="page-nav-others text-vanukin navbar navbar-dark navbar-expand-md mt-5 py-0">
            <div class="container p-md-0 w-100">
                <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#myTogglerNav" aria-controls="#myTogglerNav" aria-label="Toggle Navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <section class="collapse navbar-collapse pr-3 order-md-1 order-2" id="myTogglerNav">
                    <div class="navbar-nav ml-auto w-100 justify-content-around px-5">
                        <button type="button" class="btn-close-mobile-nav d-md-none" data-toggle="collapse" data-target="#myTogglerNav" aria-controls="#myTogglerNav" aria-label="Toggle Navigation">
                            X
                        </button>
                        <?php echo $this->render('layout/menu_all.html',NULL,get_defined_vars(),0); ?>
                    </div>
                </section>
            </div>
        </nav>

        <article id="page-impressum" class="container p-md-0 mt-5">
            <section class="layout-impressum">
                <img src="<?= ($ASSETS) ?>images/image-example.svg" alt="Image" class="img-fluid" />

                <section class="mt-5">

                    <div class="row">
                        <div class="col-md-6">     <h3><strong>Datenschutzerklärung</strong> </h3></div>
                         <div class="col-md-6 text-right">  <button onclick="window.opener=null; window.close(); return false;" class="btn btn-light rounded-0 sticky-top text-center">Schließen</button></div>
                    </div>
               

                    <div>
                        <p class="my-3">Verantwortlich für die Datenerhebung auf diesen Webseiten ist:</p>
                        <div class="impressum-paragraph">
                            <p>Pertsch GmbH (im Folgenden: „wir“)</p>
                            <p>Vertretungsberechtigte Geschäftsführer: Gabriele Diemert-Pertsch, Franz Pertsch, Wolfgang Pertsch</p>
                            <p>N7, 8 (Kunststraße)</p>
                            <p>68161 Mannheim</p>
                            <p>Telefon: 0621 / 5680-444</p>
                            <p>Fax: 0621 568228</p>
                            <p>E-Mail: <a href="mailto:webmaster@bloom-s.de">webmaster@bloom-s.de</a></p>
                        </div>
                    </div>

                    <div class="mt-5">
                        <h5><strong>1. Allgemeines</strong></h5>
                        <p class="mt-3">
                            Wir erheben und verarbeiten personenbezogene Daten, sofern Sie uns diese über ein Eingabeformular auf unserer Webseite oder auf anderem Wege, z.B. per E-Mail zur Verfügung stellen. Außerdem erheben und verarbeiten wir Daten, die bei Ihrer Nutzung unserer
                            Webseite anfallen. Die Verarbeitung Ihrer Daten erfolgt nach Maßgabe der Bestimmungen der EU Datenschutzgrundverordnung (DS-GVO), des Bundesdatenschutzgesetzes und des Telemediengesetzes. Personenbezogene Daten sind alle Informationen,
                            die sich auf eine identifizierte oder identifizierbare natürliche Person beziehen. Im Folgenden erklären wir Ihnen im Einzelnen, wie wir welche Daten auf welcher Rechtsgrundlage erheben. Darüber hinaus erläutern wir Ihnen,
                            welche Rechte Sie haben und wie lange Ihre Daten gespeichert werden.
                        </p>
                    </div>

                    <div class="mt-5">
                        <h5><strong>2. Verarbeitung Ihrer personenbezogenen Daten bei Kontaktaufnahme, bei Vertragsschluss oder sonstiger Verwendung eines Eingabeformulars</strong></h5>
                        <p class="mt-3">
                            Wenn Sie uns personenbezogene Daten durch Kontaktaufnahme zur Verfügung stellen, z.B. per E-Mail, per Telefon oder durch Eingabe Ihrer Daten in eines unserer Eingabeformulare, verarbeiten wir die von Ihnen zur Verfügung gestellten bzw. eingegebenen Daten
                            entweder gemäß Art. 6 Abs. 1 S. 1 b) DS-GVO zum Zwecke der Vertragserfüllung oder zur Durchführung vorvertraglicher Maßnahmen, die auf Ihre Anfrage erfolgen (z.B. wenn Sie sich zu einem Seminar anmelden, einen Gutschein erwerben
                            oder einen Termin buchen) oder gemäß Art. 6 Abs. 1 S. 1 f) DS-GVO aufgrund unseres berechtigten Interesses an der Beantwortung Ihrer Anfrage.<br /> Sofern Sie uns auf eine Stellenanzeige oder im
                            Rahmen einer Initiativ-Bewerbung Ihre Daten und Bewerbungsunterlagen zukommen lassen, verarbeiten wir Ihre personenbezogenen Daten auf Grundlage von Art. 6 Abs. 1 S. 1 b) DS-GVO, § 26 BDSG. Danach ist die Verarbeitung der Daten
                            zulässig, die im Zusammenhang mit der Entscheidung über die Begründung eines Beschäftigungsverhältnisses erforderlich sind.<br /> Sollten die Daten nach Abschluss des Bewerbungsverfahrens ggf. zur Rechtsverfolgung erforderlich
                            sein, kann eine Datenverarbeitung auf Basis der Voraussetzungen von Art. 6 DS-GVO, insbesondere zur Wahrnehmung von berechtigten Interessen nach Art. 6 Abs. 1 S. 1 f) DS-GVO erfolgen. Unser Interesse besteht dann in der Geltendmachung
                            oder Abwehr von Ansprüchen.<br /> Im Falle einer Absage werden Ihre Daten nach 6 Monaten gelöscht.
                        </p>
                    </div>

                    <div class="mt-5">
                        <h5><strong>3. Verarbeitung Ihrer Daten bei Bewerbung als Modell</strong></h5>
                        <p class="mt-3">
                            Wir suchen immer Modelle für Seminare, Fotoshoots und Shows. Hierfür können Sie sich bei uns bewerben, gerne über das entsprechende Eingabeformular auf unserer Webseite. Dort fragen wir mehrere Daten von Ihnen ab, von denen die mit Sternchen gekennzeichneten
                            zwingend, die anderen jedoch freiwillig sind. Wir verwenden die von Ihnen angegebenen Daten lediglich für die Entscheidung, ob Sie für uns als Modell in Frage kommen und für die Kontaktaufnahme mit Ihnen und damit auf Basis
                            von Art. 6 Abs. 1 S. 1 b) DS-GVO zum Zwecke der Vertragserfüllung oder zur Durchführung vorvertraglicher Maßnahmen, die auf Ihre Anfrage erfolgen. Sofern wir Sie als Fotomodell akzeptieren, speichern wir Ihre Daten weiterhin
                            in unserer Datenbank mit dem Zweck, Sie auch für künftige Seminare, Fotoshoots und Shows ansprechen zu können und damit ebenfalls auf Basis von Art. 6 Abs. 1 S. 1 b) DS-GVO. Sie können selbstverständlich der Verwendung Ihrer
                            Daten jederzeit widersprechen. Lesen Sie hierzu und zu Ihren weiteren Rechten bitte auch Ziffer 8 unserer Datenschutzerklärung. Sofern Sie für uns als Modell nicht in Frage kommen, werden wir Sie unverzüglich nach unserer Entscheidung
                            informieren und Ihre Daten umgehend löschen.
                        </p>
                    </div>

                    <div class="mt-5">
                        <h5><strong>4. Verarbeitung von Daten mittels Cookies und Logfiles</strong></h5>
                        <p class="mt-3">
                            Unsere Website verwendet auf Grundlage von Art. 6 Abs. 1 S. 1 f) DS-GVO sogenannte Cookies, die teilweise nötig sind, um alle Funktionen der Webseite zu ermöglichen, aber auch um die Benutzung der Website analysieren zu können. Ein Cookie ist eine Datei,
                            die auf dem Zugriffsgerät der Nutzer (PC, Tablet, Smartphone etc.) bestimmte Informationen speichert. Wird unsere Webseite von dem entsprechenden Gerät des Nutzers aufgerufen, kann der Server unserer Webseite bzw. der des jeweiligen
                            Drittanbieters die im Cookie gespeicherten Informationen auf verschiedene Arten auswerten. Durch Cookies können z.B. Nutzer wiedererkannt oder auch Nutzerverhalten nachverfolgt werden. All dies liegt in unserem berechtigten
                            Interesse, um unsere Webseite optimal gestalten und das Webseitenerlebnis für unsere Besucher verbessern zu können. Wir benutzen dabei sowohl Session-Cookies, die nach dem Ende Ihrer Browsersitzung gelöscht werden, als auch
                            dauerhafte Cookies, die für eine bestimmte voreingestellte Dauer auf Ihrem Zugriffsgerät verbleiben.
                            <br /> Über die Einstellungen in Ihrem Browser können Sie Cookies zulassen oder deaktivieren.
                        </p>

                        <p class="m-0">
                            Unsere Website verwendet auf Grundlage von Art. 6 Abs. 1 S. 1 f) DS-GVO außerdem sog. Logfiles, in denen bei jedem Seitenaufruf Zugriffsdaten gespeichert werden. Der dabei gespeicherte Datensatz enthält die folgenden Daten:
                        </p>
                        <ul class="m-0 py-0">
                            <li>
                                Die IP-Adresse, das Datum, die Uhrzeit, auf welche Datei der Zugriff erfolgte, den Status, die Anfrage, die Ihr Browser an den Server gestellt hat, die übertragene Datenmenge sowie die Internetseite, von der Sie auf die angeforderte Seite gekommen sind
                                (Referrer), sowie
                            </li>
                            <li>
                                die Produkt- und Versionsinformationen des verwendeten Browsers, Ihr Betriebssystem sowie Ihr Herkunftsland.
                            </li>
                        </ul>
                        <p class="m-0 p-0">
                            Die Protokolldaten (Logs) werden routinemäßig nach 14 Tagen überschrieben, damit also alle enthaltenen Daten unwiederbringlich gelöscht. Diese vorübergehende Speicherung erfolgt nur, um unsere Webseiten gegen Angriffe und Missbrauch schützen zu können.
                            Hierin liegt auch unser berechtigtes Interesse für die Speicherung. Wir verwenden die Protokolldaten darüber hinaus für keine anderen Zwecke.
                        </p>
                    </div>

                    <div class="mt-5">
                        <h5><strong>5. Verschlüsselung</strong></h5>
                        <p class="mt-3">
                            Sofern Sie auf unserer Webseite personenbezogenen Daten eingeben können, werden diese mittels SSL-Verschlüsselung über das Internet übertragen. Wir sichern unsere Webseite und sonstigen Systeme durch technische und organisatorische Maßnahmen gegen Verlust,
                            Zerstörung, Zugriff, Veränderung oder Verbreitung Ihrer Daten durch unbefugte Personen.
                        </p>
                    </div>

                    <div class="mt-5">
                        <h5><strong>6. Kategorien von Empfängern von Daten; Datenübermittlungen in ein Drittland</strong></h5>
                        <p class="mt-3">
                            Von uns im Zusammenhang mit der Webseite eingesetzte Dienstleister und Erfüllungsgehilfen, z.B. Host Provider, Agenturen, IT-Dienstleister sowie der in Ziffer 4 dieser Datenschutzerklärung genannte Anbieter, können Zugriff auf Ihre personenbezogenen Daten
                            haben. Sofern diese Dienstleister und Erfüllungsgehilfen für uns Daten im Auftrag verarbeiten, handeln sie weisungsgebunden und werden von uns entsprechend vertraglich verpflichtet. Eine Datenübermittlung in ein Drittland findet
                            an den unter Ziffer 4 genannten Dienstleister statt.<br /> Empfänger Ihrer Daten sind außerdem unsere Zahlungs- oder Versanddienstleister, wenn Sie auf unserer Webseite einen Gutschein erwerben.
                            Diese verarbeiten Ihre Daten aber in eigener Verantwortung und sind daher selbst verantwortliche Stellen. Die Datenübermittlung an diese erfolgt auf Grundlage von Art. 6 Abs. 1 S. 1 b) DS-GVO zum Zwecke der Erfüllung des Vertrags
                            zwischen Ihnen und uns.
                        </p>
                    </div>

                    <div class="mt-5">
                        <h5><strong>7. Ihre Rechte</strong></h5>
                        <p class="mt-3">
                            Sie haben gem. Art 15 DS-GVO das Recht, auf Antrag unentgeltlich Auskunft zu erhalten über die personenbezogenen Daten, die über Sie gespeichert wurden. Sie haben zudem gem. Art. 16, 17 und 18 DS-GVO das Recht auf Berichtigung unrichtiger Daten sowie
                            Sperrung und Löschung Ihrer personenbezogenen Daten. <br /> Sie sind unter den in Art. 20 DS-GVO bestimmten Voraussetzungen außerdem berechtigt, die Sie betreffenden personenbezogenen Daten, die
                            gespeichert wurden, in einem strukturierten, gängigen und maschinenlesbaren Format zu erhalten und diese Daten einem anderen Verantwortlichen ohne Behinderung durch uns zu übermitteln. <br /> Außerdem sind Sie gem. Art. 21
                            Abs. 1 DS-GVO berechtigt, gegen die Verarbeitung Sie betreffender personenbezogener Daten, die aufgrund von Art. 6 Abs. 1 S. 1 e) oder f) DS-GVO erfolgt, einschließlich Profiling, aus Gründen, die sich aus Ihrer besonderen
                            Situation ergeben, Widerspruch einzulegen. Wenn Ihre personenbezogenen Daten für Zwecke der Direktwerbung verarbeitet werden, haben Sie gemäß Art. 21 Abs. 2 DS-GVO jederzeit das Recht der Verarbeitung Ihrer Daten für derartige
                            Werbung, einschließlich Profiling, soweit es mit solcher Direktwerbung in Verbindung steht, zu widersprechen.

                            <br />
                            <br /> Wir werden Ihre vorgenannten Rechte erfüllen, soweit die gesetzlichen Voraussetzungen für die Geltendmachung der Rechte gegeben sind. <br /> Jegliche Ersuchen zu Ihren personenbezogenen Daten richten Sie bitte an die
                            zu Beginn dieser Datenschutzerklärung, aber auch im Impressum unserer Webseite angegebenen Kontaktdaten. <br /> Jeder Betroffene hat außerdem das Recht, bei einer Datenschutzaufsichtsbehörde eine Beschwerde über die Verarbeitung
                            von Daten durch uns einzulegen.
                        </p>
                    </div>

                    <div class="mt-5">
                        <h5><strong>8. Dauer der Speicherung und routinemäßige Löschung</strong></h5>
                        <p class="mt-3">
                            Sofern in dieser Datenschutzerklärung bei der konkreten Datenverarbeitung nicht anders angegeben, verarbeiten und speichern wir personenbezogene Daten nur für den Zeitraum, der zur Erreichung des Verarbeitungszwecks erforderlich ist oder sofern dies in
                            Gesetzen oder Vorschriften, welchen wir unterliegen, vorgesehen wurde. <br /> Entfällt der Speicherungszweck oder läuft eine gesetzlich vorgeschriebene Speicherfrist ab, werden die personenbezogenen
                            Daten routinemäßig und entsprechend den gesetzlichen Vorschriften gesperrt oder gelöscht.
                        </p>

                    </div>
                </section>
            </section>
        </article>
    </div>
</div>