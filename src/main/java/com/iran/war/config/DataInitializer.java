package com.iran.war.config;

import com.iran.war.entity.Article;
import com.iran.war.entity.Author;
import com.iran.war.entity.Category;
import com.iran.war.entity.User;
import com.iran.war.repository.ArticleRepository;
import com.iran.war.repository.AuthorRepository;
import com.iran.war.repository.CategoryRepository;
import com.iran.war.repository.UserRepository;
import com.iran.war.service.ArticleService;
import lombok.RequiredArgsConstructor;
import lombok.extern.slf4j.Slf4j;
import org.springframework.boot.CommandLineRunner;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.stereotype.Component;

import java.time.LocalDateTime;
import java.util.List;

@Component
@RequiredArgsConstructor
@Slf4j
public class DataInitializer implements CommandLineRunner {

    private final CategoryRepository categoryRepository;
    private final AuthorRepository authorRepository;
    private final ArticleRepository articleRepository;
    private final UserRepository userRepository;
    private final PasswordEncoder passwordEncoder;

    @Override
    public void run(String... args) {
        if (userRepository.count() == 0) {
            initData();
            log.info("Sample data initialized.");
        }
    }

    private void initData() {
        // Admin user
        User admin = new User("admin", passwordEncoder.encode("admin123"), "admin@iran-war-info.com");
        userRepository.save(admin);

        // Categories
        Category catConflict = categoryRepository.save(new Category(
                "Conflit & Opérations militaires",
                "conflit-operations-militaires",
                "Actualités sur les opérations militaires et les combats en Iran et dans la région"
        ));
        Category catDiplomatie = categoryRepository.save(new Category(
                "Diplomatie & Politique",
                "diplomatie-politique",
                "Enjeux diplomatiques et décisions politiques liés au conflit iranien"
        ));
        Category catHumanitaire = categoryRepository.save(new Category(
                "Humanitaire",
                "humanitaire",
                "Situation humanitaire, réfugiés et aide internationale"
        ));
        Category catAnalyse = categoryRepository.save(new Category(
                "Analyses & Décryptages",
                "analyses-decryptages",
                "Analyses approfondies et contexte géopolitique du conflit"
        ));
        Category catEconomie = categoryRepository.save(new Category(
                "Économie & Sanctions",
                "economie-sanctions",
                "Impact économique des sanctions internationales sur l'Iran"
        ));

        // Authors
        Author author1 = authorRepository.save(new Author(
                "Sophie Martin",
                "s.martin@iran-war-info.com",
                "Correspondante au Moyen-Orient depuis 2015, spécialisée dans les conflits régionaux."
        ));
        Author author2 = authorRepository.save(new Author(
                "Jean-Pierre Dumont",
                "jp.dumont@iran-war-info.com",
                "Analyste géopolitique, expert des relations internationales et de la région du Golfe persique."
        ));
        Author author3 = authorRepository.save(new Author(
                "Leila Ahmadi",
                "l.ahmadi@iran-war-info.com",
                "Journaliste franco-iranienne, couvre la situation en Iran depuis Téhéran et Paris."
        ));

        // Articles
        createArticle(
                "Escalade des tensions : situation au front nord de l'Iran",
                "escalade-tensions-front-nord-iran",
                "Les combats s'intensifient dans la région nord de l'Iran, avec de nouveaux rapports faisant état d'affrontements majeurs.",
                "<p>Les tensions ont considérablement augmenté dans la région nord de l'Iran cette semaine, avec des rapports confirmant des affrontements intensifiés entre les forces armées iraniennes et les groupes opposés au régime. Selon des sources militaires et des témoins sur place, les combats se concentrent principalement autour des zones frontalières nord-ouest du pays.</p>" +
                "<p>Les autorités iraniennes ont déclaré l'état d'alerte maximale dans plusieurs provinces, mobilisant des unités supplémentaires des Gardiens de la Révolution islamique (IRGC). Les routes principales vers les zones de conflit ont été fermées à la circulation civile, compliquant l'acheminement de l'aide humanitaire.</p>" +
                "<p>Les observateurs internationaux de l'ONU présents dans la région font état de déplacements de population importants, avec des milliers de civils fuyant les zones de combat vers les villes plus sûres du centre du pays. Les hôpitaux de Tabriz et d'Urmia sont sous forte pression, recevant des afflux massifs de blessés.</p>" +
                "<p>La communauté internationale suit de près l'évolution de la situation, avec des appels répétés au cessez-le-feu de la part de plusieurs gouvernements occidentaux et d'organisations humanitaires. Cependant, aucune négociation formelle n'est pour l'instant engagée.</p>",
                catConflict, author1,
                List.of("conflit", "Iran", "opérations militaires", "nord Iran"),
                "Escalade des tensions au front nord de l'Iran",
                "Dernières informations sur l'escalade militaire au nord de l'Iran. Combats intensifiés, déplacements de population et réactions internationales.",
                "Iran, guerre, conflit, tensions, opérations militaires, IRGC"
        );

        createArticle(
                "Négociations diplomatiques : l'Europe tente une médiation",
                "negociations-diplomatiques-europe-mediation",
                "Les puissances européennes cherchent à jouer un rôle de médiateur pour désamorcer la crise iranienne.",
                "<p>Face à l'escalade du conflit en Iran, les grandes puissances européennes — France, Allemagne, Royaume-Uni — ont lancé une initiative diplomatique conjointe pour tenter de ramener les parties à la table des négociations. Le chef de la diplomatie de l'Union européenne s'est rendu à Téhéran dans le cadre de cette médiation.</p>" +
                "<p>La mission diplomatique européenne s'articule autour de trois axes principaux : un cessez-le-feu humanitaire de 72 heures pour permettre l'évacuation des civils piégés dans les zones de combat, la reprise des négociations sur le programme nucléaire iranien, et la mise en place d'un mécanisme international de surveillance du conflit.</p>" +
                "<p>Le gouvernement iranien a accueilli la délégation européenne avec une certaine réticence, insistant sur sa souveraineté nationale et rejetant toute ingérence étrangère dans ce qu'il qualifie d'« affaires internes ». Néanmoins, des sources diplomatiques indiquent que des discussions substantielles ont eu lieu à huis clos.</p>" +
                "<p>Du côté américain, Washington surveille attentivement ces développements mais a jusqu'ici refusé de participer directement aux pourparlers, invoquant la nécessité de « conditions préalables » à toute négociation avec Téhéran. La Russie et la Chine, pour leur part, ont appelé à une solution négociée tout en s'opposant à toute sanction supplémentaire.</p>",
                catDiplomatie, author2,
                List.of("diplomatie", "Iran", "Europe", "médiation", "négociations"),
                "Négociations diplomatiques européennes sur la crise iranienne",
                "L'Europe tente une médiation diplomatique pour résoudre la crise iranienne. Analyse des enjeux et des positions des différentes parties.",
                "Iran, diplomatie, Europe, médiation, négociations, cessez-le-feu"
        );

        createArticle(
                "Crise humanitaire : des millions de civils dans le besoin",
                "crise-humanitaire-civils-besoin",
                "La guerre en Iran provoque une crise humanitaire sans précédent, avec des millions de civils déplacés ou privés de ressources essentielles.",
                "<p>Le Haut-Commissariat des Nations Unies pour les réfugiés (HCR) a tiré la sonnette d'alarme sur la situation humanitaire en Iran, qualifiant la crise de « l'une des plus graves de la décennie ». Selon les derniers chiffres publiés, plus de 3,5 millions de personnes ont été déplacées à l'intérieur du pays depuis le début des hostilités.</p>" +
                "<p>Les infrastructures civiles ont été gravement endommagées dans les zones de conflit. Des hôpitaux, des écoles et des réseaux d'approvisionnement en eau ont été touchés par les combats, privant des populations entières d'accès aux services de base. Le Programme alimentaire mondial (PAM) estime que près de 8 millions de personnes souffrent d'insécurité alimentaire grave.</p>" +
                "<p>L'accès humanitaire reste l'un des principaux défis. Les organisations non gouvernementales signalent des difficultés considérables pour acheminer l'aide dans les zones les plus touchées en raison des restrictions imposées par les autorités militaires et de l'insécurité générale. Médecins Sans Frontières a dénoncé plusieurs incidents ayant visé ses convois.</p>" +
                "<p>Les pays voisins — Irak, Turquie, Afghanistan et Pakistan — font face à une pression migratoire croissante. La Turquie a déclaré avoir accueilli plus de 400 000 réfugiés iraniens supplémentaires ces trois derniers mois, et appelle la communauté internationale à partager la responsabilité de cet accueil.</p>",
                catHumanitaire, author3,
                List.of("humanitaire", "Iran", "réfugiés", "crise", "ONU"),
                "Crise humanitaire en Iran : millions de civils dans le besoin",
                "La guerre en Iran provoque une crise humanitaire grave. Déplacements de population, manque de ressources et difficultés d'accès humanitaire.",
                "Iran, humanitaire, réfugiés, crise, ONU, HCR, aide internationale"
        );

        createArticle(
                "Géopolitique iranienne : les racines profondes du conflit",
                "geopolitique-iranienne-racines-conflit",
                "Pour comprendre la guerre en Iran, il faut remonter aux décennies de tensions régionales, religieuses et politiques qui ont façonné ce pays.",
                "<p>La crise actuelle en Iran n'est pas apparue du jour au lendemain. Elle est le produit de décennies de tensions internes et de rivalités régionales qui ont progressivement érodé la stabilité du pays. Pour comprendre les événements actuels, il est indispensable d'analyser le contexte historique et géopolitique dans lequel ils s'inscrivent.</p>" +
                "<p><strong>Les fractures internes</strong> : L'Iran est un pays aux multiples identités — persane, arabe, kurde, azérie, baloutche — dont les relations avec le pouvoir central de Téhéran ont toujours été complexes. Les minorités ethniques et religieuses, souvent marginalisées économiquement et politiquement, constituent des sources de tension chronique. Les Kurdes iraniens, en particulier, revendiquent depuis des décennies une plus grande autonomie, voire l'indépendance.</p>" +
                "<p><strong>La rivalité chiite-sunnite</strong> : L'Iran chiite se perçoit comme le champion de l'islam chiite face aux pays sunnites du Golfe, au premier rang desquels l'Arabie saoudite. Cette rivalité confessionnelle se projette sur l'ensemble des conflits régionaux, du Yémen à la Syrie, en passant par l'Irak et le Liban. Elle constitue un des principaux vecteurs de déstabilisation de la région.</p>" +
                "<p><strong>Le programme nucléaire</strong> : La question du nucléaire iranien demeure un facteur de tension majeur avec la communauté internationale. Malgré les accords successifs (JCPOA de 2015 puis tentatives de relance), Téhéran n'a jamais complètement abandonné ses ambitions dans ce domaine, alimentant les craintes d'une course aux armements régionale.</p>" +
                "<p><strong>Les sanctions économiques</strong> : L'isolement économique imposé par les sanctions internationales a considérablement fragilisé la société iranienne, appauvrissant une classe moyenne autrefois dynamique et alimentant un sentiment croissant de frustration et de colère envers le régime.</p>",
                catAnalyse, author2,
                List.of("géopolitique", "Iran", "analyse", "conflit", "histoire"),
                "Analyse géopolitique : les racines du conflit en Iran",
                "Analyse approfondie des causes historiques, géopolitiques et sociales du conflit iranien. Comprendre les fractures internes et les enjeux régionaux.",
                "Iran, géopolitique, analyse, conflit, histoire, nucléaire, sanctions"
        );

        createArticle(
                "Sanctions économiques : l'Iran sous pression financière",
                "sanctions-economiques-iran-pression-financiere",
                "Les sanctions internationales imposées à l'Iran ont des répercussions profondes sur son économie et sa population.",
                "<p>Le régime de sanctions internationales pesant sur l'Iran — imposé par les États-Unis, l'Union européenne et de nombreux autres pays — constitue l'un des plus complets jamais mis en place dans l'histoire récente. Ces mesures couvrent le secteur énergétique, le système bancaire, les exportations d'armement et les transactions financières internationales.</p>" +
                "<p>L'impact sur l'économie iranienne est considérable. Le rial iranien a perdu plus de 80% de sa valeur depuis le renforcement des sanctions en 2018. L'inflation a atteint des niveaux record, dépassant 40% annuellement. Les exportations de pétrole, pilier traditionnel de l'économie iranienne, ont été réduites à une fraction de leur niveau antérieur.</p>" +
                "<p>La population iranienne est la première victime de cette situation. Les prix des produits de première nécessité ont explosé, l'accès aux médicaments et aux équipements médicaux est devenu difficile, et le chômage touche massivement les jeunes diplômés. Cette précarité économique croissante alimente les tensions sociales et les manifestations.</p>" +
                "<p>Face à ces pressions, Téhéran a cherché des stratégies de contournement : développement d'une économie de résistance, commerce informel avec des pays amis, et exploitation de failles dans le dispositif de sanctions. La Chine et la Russie maintiennent des relations commerciales avec l'Iran, en partie en violation des sanctions occidentales.</p>" +
                "<p>La question de l'efficacité des sanctions pour induire un changement de comportement de Téhéran reste très débattue parmi les experts. Si elles ont indéniablement affaibli l'économie iranienne, leur impact sur les décisions politiques du régime semble plus limité, certains analystes estimant qu'elles ont au contraire renforcé les factions les plus radicales.</p>",
                catEconomie, author1,
                List.of("économie", "Iran", "sanctions", "finance", "rial"),
                "Sanctions économiques contre l'Iran : impact et conséquences",
                "Analyse de l'impact des sanctions internationales sur l'économie iranienne et sa population. Conséquences sociales et stratégies de contournement.",
                "Iran, sanctions, économie, finance, rial, inflation, pétrole"
        );

        log.info("Articles, categories and authors created successfully.");
    }

    private void createArticle(String title, String slug, String summary, String content,
                                Category category, Author author, List<String> tags,
                                String metaTitle, String metaDescription, String metaKeywords) {
        Article article = new Article();
        article.setTitle(title);
        article.setSlug(slug);
        article.setSummary(summary);
        article.setContent(content);
        article.setCategory(category);
        article.setAuthor(author);
        article.setTags(tags);
        article.setMetaTitle(metaTitle);
        article.setMetaDescription(metaDescription);
        article.setMetaKeywords(metaKeywords);
        article.publish();
        articleRepository.save(article);
    }
}
