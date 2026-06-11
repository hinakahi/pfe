<!doctype html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SiOukli — Résidence Universitaire Si Oukli · Tamda, Tizi Ouzou</title>
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  </head>
  <body>
    <!-- ══════════ NAVBAR ══════════ -->
    <header class="header" id="header">
      <nav class="nav-container">
        <a href="#accueil" class="logo-container">
          <div class="logo-icon">
    <img src="{{ asset('photo/mon_logo.jpg') }}" alt="Logo SiOukli">
      </div>
          <div>
            <span class="logo-text">Si<span>Oukli</span></span>
            <small class="logo-sub">Résidence kasri Mohammed · Tamda</small>
          </div>
        </a>
        <ul class="nav-menu" id="navMenu">
          <li><a href="#accueil" class="nav-link active">Accueil</a></li>
          <li><a href="#services" class="nav-link">Services</a></li>
          <li><a href="#apropos" class="nav-link">À propos</a></li>
          <li><a href="#contact" class="nav-link">Contact</a></li>
          <li>
            <a href="{{ route('login') }}" class="nav-link btn-login">
              <i class="fas fa-sign-in-alt"></i> Se connecter
            </a>
          </li>
        </ul>
        <button class="hamburger" id="hamburger">
          <span></span><span></span><span></span>
        </button>
      </nav>
    </header>

    <!-- ══════════ HERO SLIDER ══════════ -->
    <section id="accueil" class="hero">
      <div class="particles" id="particles"></div>
      <div class="slider-wrapper">
        <div class="slider-container">
          <div class="progress-ring">
            <div class="progress-bar" id="progressBar"></div>
          </div>
          <div class="slider-stage" id="sliderStage"></div>
          <div class="text-overlay" id="textOverlay">
            <span class="slide-badge" id="slideBadge">Résidence Si Oukli</span>
            <h2 class="slide-title" id="slideTitle">Bienvenue à la Résidence Si Oukli</h2>
            <p class="slide-description" id="slideDescription">
              Votre espace de vie universitaire au cœur du pôle de Tamda, Tizi Ouzou.
            </p>
          </div>
          <button class="nav-arrow prev" id="prevArrow"><i class="fas fa-chevron-left"></i></button>
          <button class="nav-arrow next" id="nextArrow"><i class="fas fa-chevron-right"></i></button>
          <div class="controls">
            <button class="control-btn" id="playPauseBtn" title="Pause/Lecture">
              <i class="fas fa-pause" id="playIcon"></i>
            </button>
          </div>
          <div class="dots" id="dots"></div>
          <div class="thumbnails-container" id="thumbnails"></div>
        </div>
      </div>
    </section>

    <!-- ══════════ SERVICES ══════════ -->
    <section id="services" class="section services">
      <div class="section-container">
        <div class="section-header reveal">
          <span class="section-tag">Ce que nous offrons</span>
          <h2 class="section-title">Tous vos services, en un clic</h2>
          <p class="section-subtitle">
            La résidence Si Oukli met à votre disposition une gamme complète de
            services pour un séjour universitaire serein et confortable.
          </p>
        </div>
        <div class="services-container">
          <div class="services-tabs reveal-left">
            <div class="service-tab active" data-service="hebergement">
              <span class="service-tab-icon">🏠</span>
              <h3>Hébergement</h3>
              <p>Chambres doubles & individuelles</p>
            </div>
            <div class="service-tab" data-service="restauration">
              <span class="service-tab-icon">🍽️</span>
              <h3>Restauration</h3>
              <p>3 repas par jour, menus variés</p>
            </div>
            <div class="service-tab" data-service="bibliotheque">
              <span class="service-tab-icon">📚</span>
              <h3>Bibliothèque</h3>
              <p>Catalogue & prêt de livres en ligne</p>
            </div>
            <div class="service-tab" data-service="activites">
              <span class="service-tab-icon">🎭</span>
              <h3>Activités culturelles</h3>
              <p>Sport, culturel, mussala</p>
            </div>
            <div class="service-tab" data-service="sante">
              <span class="service-tab-icon">🩺</span>
              <h3>Santé</h3>
              <p>RDV médecin & psychologue</p>
            </div>
          </div>
          <div class="services-content reveal-right">
            <div class="service-content active" id="hebergement">
              <div class="service-content-icon">🏠</div>
              <h2>Hébergement <span>confortable</span></h2>
              <p>La résidence Si Oukli dispose de <strong>2000 lits</strong> répartis dans 8 pavillons modernes. Chaque résidente bénéficie d'un espace sécurisé et bien équipé.</p>
              <p>Via l'application, vous pouvez demander un changement de chambre, signaler un problème ou suivre vos demandes en temps réel.</p>
              <ul class="service-features">
                <li>Chambres doubles et individuelles (cas exceptionnels)</li>
                <li>Demande de changement de chambre en ligne</li>
                <li>Signalement de pannes & maintenance numérique</li>
                <li>Suivi en temps réel du statut de vos demandes</li>
                <li>Attribution selon critères ONOU (distance, dossier social)</li>
              </ul>
            </div>
            <div class="service-content" id="restauration">
              <div class="service-content-icon">🍽️</div>
              <h2>Restauration <span>universitaire</span></h2>
              <p>La cantine de la résidence propose trois repas équilibrés par jour. Consultez les menus hebdomadaires et les horaires directement depuis votre espace étudiant.</p>
              <p>Le responsable restauration publie les menus chaque semaine et répond à vos réclamations et avis en ligne.</p>
              <ul class="service-features">
                <li>Menus hebdomadaires publiés en ligne</li>
                <li>Horaires petit-déjeuner, déjeuner et dîner</li>
                <li>Consultation sans déplacement</li>
                <li>Système d'avis et de réclamations intégré</li>
                <li>Notifications en cas de changement de menu</li>
              </ul>
            </div>
            <div class="service-content" id="bibliotheque">
              <div class="service-content-icon">📚</div>
              <h2>Bibliothèque <span>numérique</span></h2>
              <p>Accédez au catalogue complet des ouvrages disponibles, vérifiez la disponibilité d'un livre et effectuez votre réservation en quelques clics depuis votre espace.</p>
              <p>Plus besoin de se déplacer pour savoir si un ouvrage est disponible — le système gère tout automatiquement.</p>
              <ul class="service-features">
                <li>Catalogue en ligne avec disponibilité en temps réel</li>
                <li>Réservation de livres sans file d'attente</li>
                <li>Délai de retrait 24h après confirmation</li>
                <li>Rappels automatiques avant date de retour (J+15)</li>
                <li>Historique complet de vos emprunts</li>
              </ul>
            </div>
            <div class="service-content" id="activites">
              <div class="service-content-icon">🎭</div>
              <h2>Activités <span>culturelles & islamiques</span></h2>
              <p>Inscrivez-vous aux activités culturelles, sportives et islamiques organisées au sein de la résidence. Restez informée des événements à venir grâce aux notifications.</p>
              <p>Le foyer de la résidence propose également un catalogue d'articles (fast-food, cafétéria, magasin) réservables en ligne.</p>
              <ul class="service-features">
                <li>Activités culturelles & sportives (inscription en ligne)</li>
                <li>Espace Mussala : horaires de prière & cercles Coran</li>
                <li>Foyer : catalogue articles & réservations</li>
                <li>Notifications d'acceptation ou de refus automatiques</li>
                <li>Historique de vos inscriptions</li>
              </ul>
            </div>
            <div class="service-content" id="sante">
              <div class="service-content-icon">🩺</div>
              <h2>Unité de <span>santé intégrée</span></h2>
              <p>Prenez rendez-vous en ligne avec le médecin ou le psychologue de la résidence. Fini les longues files d'attente — choisissez votre créneau disponible directement.</p>
              <p>L'unité de santé gère son calendrier de disponibilités et vous envoie une confirmation automatique pour chaque rendez-vous.</p>
              <ul class="service-features">
                <li>Prise de RDV médecin & psychologue en ligne</li>
                <li>Consultation des créneaux disponibles en temps réel</li>
                <li>Confirmation automatique par notification</li>
                <li>Réduction des pics d'affluence à l'unité de santé</li>
                <li>Notes médicales internes confidentielles</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ══════════ À PROPOS ══════════ -->
    <section id="apropos" class="section about">
      <div class="section-container">
        <div class="section-header reveal">
          <span class="section-tag" style="color: var(--sky3); background: rgba(56, 189, 248, 0.1); border-color: rgba(56, 189, 248, 0.2);">Notre histoire</span>
          <h2 class="section-title">La résidence Si Oukli en chiffres</h2>
          <p class="section-subtitle">Kasri Mohammed Akli — dit Si Oukli — accompagne les étudiantes de l'UMMTO depuis 2009.</p>
        </div>
        <div class="about-grid">
          <div class="about-timeline reveal-left">
            <div class="timeline-line"></div>
            <div class="timeline-item">
              <div class="timeline-dot"></div>
              <div class="timeline-year">2009</div>
              <div class="timeline-title">Mise en service de la résidence</div>
              <div class="timeline-description">
                Ouverture de la résidence universitaire Kasri Mohammed Akli (Tamda 1) rattachée à la DOU de Tamda pour répondre à la demande croissante en hébergement universitaire.
              </div>
            </div>
            <div class="timeline-item">
              <div class="timeline-dot"></div>
              <div class="timeline-year">2026</div>
              <div class="timeline-title">Projet de numérisation Si Oukli</div>
              <div class="timeline-description">
                Lancement du projet de modernisation numérique visant à digitaliser tous les services : hébergement, maintenance, bibliothèque, santé, restauration et activités culturelles.
              </div>
            </div>
            <div class="timeline-item">
              <div class="timeline-dot"></div>
            </div>
          </div>
          <div class="about-tech reveal-right">
            <div class="tech-card">
              <div class="tech-icon">🚀</div>
              <div class="tech-title">Zéro papier, 100% numérique</div>
              <div class="tech-description">Toutes les demandes (maintenance, chambre, bibliothèque, RDV médical) sont traitées en ligne. Fini les notes manuscrites et les files d'attente inutiles.</div>
            </div>
            <div class="tech-card">
              <div class="tech-icon">🔔</div>
              <div class="tech-title">Notifications en temps réel</div>
              <div class="tech-description">L'administration peut diffuser des alertes urgentes (coupures, événements) directement aux 1318 résidentes — remplaçant l'affichage physique limité.</div>
            </div>
            <div class="tech-card">
              <div class="tech-icon">📊</div>
              <div class="tech-title">Tableau de bord & KPI</div>
              <div class="tech-description">L'administration dispose d'indicateurs de performance en temps réel : taux d'occupation, réclamations ouvertes, demandes en attente, statistiques de satisfaction.</div>
            </div>
          </div>
        </div>
        <div class="stats-grid reveal">
          <div class="stat-card"><h4>2000</h4><p>Lits disponibles</p></div>
          <div class="stat-card"><h4>1318</h4><p>Résidentes actuelles</p></div>
          <div class="stat-card"><h4>8</h4><p>Pavillons d'hébergement</p></div>
          <div class="stat-card"><h4>121</h4><p>Employés au service</p></div>
        </div>
      </div>
    </section>

    <!-- ══════════ CONTACT ══════════ -->
    <section id="contact" class="section contact">
      <div class="section-container">
        <div class="section-header reveal">
          <span class="section-tag">Nous contacter</span>
          <h2 class="section-title">Une question ? Écrivez-nous</h2>
          <p class="section-subtitle">Pour toute demande d'information concernant la résidence Si Oukli ou la plateforme TAMDA 1.</p>
        </div>
        <div class="contact-content">
         <div class="contact-form reveal-left">

    @if(session('success'))
        <div style="background:#d1fae5;color:#065f46;padding:14px 18px;border-radius:10px;margin-bottom:20px;font-weight:500;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('contact.send') }}">
        @csrf

        <div class="form-group">
            <label>Votre nom complet</label>
            <input type="text" name="nom" placeholder=" saisir votre nom svp .."
                   value="{{ old('nom') }}" />
            @error('nom')<span style="color:#ef4444;font-size:0.85rem;">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label>Adresse email</label>
            <input type="email" name="email" placeholder="saisir votre email svp..."
                   value="{{ old('email') }}" />
            @error('email')<span style="color:#ef4444;font-size:0.85rem;">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label>Objet</label>
            <select name="objet">
                <option value="Information hébergement"   {{ old('objet') == 'Information hébergement'   ? 'selected' : '' }}>Information hébergement</option>
                <option value="Problème technique"        {{ old('objet') == 'Problème technique'        ? 'selected' : '' }}>Problème technique</option>
                <option value="Accès compte"              {{ old('objet') == 'Accès compte'              ? 'selected' : '' }}>Accès compte</option>
                <option value="Autre"                     {{ old('objet') == 'Autre'                     ? 'selected' : '' }}>Autre</option>
            </select>
        </div>

        <div class="form-group">
            <label>Message</label>
            <textarea name="message" placeholder="Décrivez votre demande…">{{ old('message') }}</textarea>
            @error('message')<span style="color:#ef4444;font-size:0.85rem;">{{ $message }}</span>@enderror
        </div>

        <button type="submit" class="submit-btn">
            <i class="fas fa-paper-plane" style="margin-right: 8px"></i>Envoyer le message
        </button>
    </form>
</div>
          <div class="contact-info reveal-right">
            <div class="contact-item">
              <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
              <div class="contact-details">
                <h4>Adresse</h4>
                <p>Pôle universitaire de Tamda<br />Tizi Ouzou, Algérie</p>
              </div>
              <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d573.6880237066865!2d4.196465435229105!3d36.710720906407225!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x128dbb0044c1c6a7%3A0x871fca11d9bc1e6!2sR%C3%A9sidence%20fille%20tamda%201!5e1!3m2!1sfr!2sdz!4v1779619757932!5m2!1sfr!2sdz" width="300" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            <div class="contact-item">
              <div class="contact-icon"><i class="fas fa-university"></i></div>
              <div class="contact-details">
                <h4>Rattachement</h4>
                <p>DOU de Tamda<br />Université Mouloud Mammeri (UMMTO)</p>
              </div>
            </div>
            <div class="contact-item">
              <div class="contact-icon"><i class="fas fa-clock"></i></div>
              <div class="contact-details">
                <h4>Horaires d'accueil</h4>
                <p>Dimanche – Jeudi : 8h00 – 15h30
              </div>
            </div>
            <div class="contact-item">
              <div class="contact-icon"><i class="fas fa-shield-alt"></i></div>
              <div class="contact-details">
                <h4>Conditions d'accès</h4>
                <p>Inscription UMMTO + résidence familiale<br />à plus de 30 km de Tizi Ouzou</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ══════════ FOOTER ══════════ -->
    <footer class="footer">
      <div class="footer-content">
        <div class="footer-main">
          <div>
            <div class="footer-logo-wrap">
               <a href="#accueil" class="logo-container">
          <div class="logo-icon">
    <img src="{{ asset('photo/mon_logo.jpg') }}" alt="Logo SiOukli">
      </div>
          <div>
            <span class="logo-text">Si<span>Oukli</span></span>
            <small class="logo-sub">Résidence kasri Mohammed · Tamda</small>
          </div>
        </a>
             
             
            </div>
            <p class="footer-description">
              Plateforme numérique de gestion de la résidence universitaire Kasri Mohammed Akli (Si Oukli) — Tamda, Tizi Ouzou. Un écosystème digital pour les 1318 résidentes.
            </p>
            <div class="footer-social">
              <a href="https://www.facebook.com/dz.onou.15.dout.rut1" class="social-link"><i class="fab fa-facebook-f"></i></a>
            </div>
          </div>
          <div class="footer-section">
            <h4>Services</h4>
            <div class="footer-links">
              <a href="#services" class="footer-link">Hébergement</a>
              <a href="#services" class="footer-link">Restauration</a>
              <a href="#services" class="footer-link">Bibliothèque</a>
              <a href="#services" class="footer-link">Santé</a>
              <a href="#services" class="footer-link">Activités</a>
            </div>
          </div>
          <div class="footer-section">
            <h4>Accès rapide</h4>
            <div class="footer-links">
              <a href="{{ route('login') }}" class="footer-link">Se connecter</a>
              <a href="{{ route('login') }}" class="footer-link">S'inscrire</a>
              <a href="#apropos" class="footer-link">À propos</a>
              <a href="#contact" class="footer-link">Contact</a>
            </div>
          </div>
          <div class="footer-section">
            <h4>Administration</h4>
            <div class="footer-links">
              <a href="https://progres.mesrs.dz/webetu/" target="_blank" class="footer-link">PROGRES MESRS</a>
              <a href="#" class="footer-link">DOU Tamda</a>
              <a href="#" class="footer-link">UMMTO</a>
              <a href="#" class="footer-link">ONOU</a>
            </div>
          </div>
        </div>
        <div class="footer-bottom">
          <div class="footer-copyright">© 2026 — Résidence Si Oukli, Tamda · Tizi Ouzou</div>
          <div class="footer-copyright">Développé dans le cadre du PFE — Université Mouloud Mammeri</div>
        </div>
      </div>
    </footer>

    <script>
      /* ══════════ PARTICULES ══════════ */
      (function () {
        const c = document.getElementById("particles");
        for (let i = 0; i < 28; i++) {
          const p = document.createElement("div");
          p.className = "particle";
          const s = Math.random() * 8 + 3;
          p.style.cssText = `width:${s}px;height:${s}px;left:${Math.random() * 100}%;top:${Math.random() * 100}%;animation-duration:${Math.random() * 8 + 6}s;animation-delay:${Math.random() * 6}s;background:rgba(${Math.random() > 0.5 ? "56,189,248" : "14,165,233"},${Math.random() * 0.5 + 0.2})`;
          c.appendChild(p);
        }
      })();

      /* ══════════ IMAGES SLIDER ══════════ */
      const SLIDES = [
        {
          url: "{{ asset('photo/7.jpg') }}",
          thumb: "{{ asset('photo/577790172_821772173946876_8246481439298717551_n.jpg') }}",
          badge: "Résidence Si Oukli",
          title: "Bienvenue à la Résidence Si Oukli",
          description: "2000 lits, 8 pavillons modernes — votre espace de vie universitaire à Tamda, Tizi Ouzou.",
        },
        {
          url: "{{ asset('photo/564651935_802743812516379_3548021131265954541_n.jpg') }}",
          thumb: "{{ asset('photo/564651935_802743812516379_3548021131265954541_n.jpg') }}",
          badge: "Bibliothèque",
          title: "Catalogue numérique en ligne",
          description: "Réservez vos livres sans file d'attente — vérifiez la disponibilité et récupérez sous 24h.",
        },
        {
          url: "{{ asset('photo/5.png') }}",
          thumb: "{{ asset('photo/5.png') }}",
          badge: "Services intégrés",
          title: "Tous vos services en un clic",
          description: "Maintenance, restauration, santé, foyer, activités culturelles — tout depuis votre espace personnel.",
        },
        {
          url: "{{ asset('photo/3.png') }}",
          thumb: "{{ asset('photo/3.png') }}",
          badge: "Services intégrés",
          title: "Tous vos services en un clic",
          description: "Maintenance, restauration, santé, foyer, activités culturelles — tout depuis votre espace personnel.",
        },
        {
          url: "{{ asset('photo/548127153_778558304934930_5767677889783360502_n.jpg') }}",
          thumb: "{{ asset('photo/548127153_778558304934930_5767677889783360502_n.jpg') }}",
          badge: "Santé",
          title: "Unité de santé numérique",
          description: "Prenez rendez-vous avec le médecin ou le psychologue en quelques secondes — disponible 24h/24.",
        },
        {
          url: "{{ asset('photo/6.png') }}",
          thumb: "{{ asset('photo/6.png') }}",
          badge: "Activités culturelles",
          title: "Vie culturelle & islamique",
          description: "Inscrivez-vous aux activités sportives, culturelles et à la Mussala — horaires de prière en temps réel.",
        },
        {
          url: "{{ asset('photo/571316701_809531668504260_2327986354187602315_n.jpg') }}",
          thumb: "{{ asset('photo/571316701_809531668504260_2327986354187602315_n.jpg') }}",
          badge: "Activités culturelles",
          title: "Vie culturelle & islamique",
          description: "Inscrivez-vous aux activités sportives, culturelles et à la Mussala — horaires de prière en temps réel.",
        },
      ];

      /* ══════════ SLIDER LOGIC ══════════ */
      class SiOukliSlider {
        constructor() {
          this.idx = 0;
          this.anim = false;
          this.slices = 10;
          this.timer = null;
          this.playing = true;
          this.face = 0;
          this.init();
        }
        init() {
          this.buildSlices();
          this.buildDots();
          this.buildThumbs();
          this.events();
          this.loadImages();
          this.play();
        }
        buildSlices() {
          const st = document.getElementById("sliderStage");
          const W = st.offsetWidth || 1100;
          for (let i = 0; i < this.slices; i++) {
            const sc = document.createElement("div");
            sc.className = "slice-container";
            const cube = document.createElement("div");
            cube.className = "slice-cube";
            for (let f = 1; f <= 4; f++) {
              const face = document.createElement("div");
              face.className = `slice-face face-${f}`;
              const img = document.createElement("div");
              img.className = "slice-image";
              img.style.width = W + "px";
              img.style.left = -(i * (W / this.slices)) + "px";
              face.appendChild(img);
              cube.appendChild(face);
            }
            sc.appendChild(cube);
            st.appendChild(sc);
          }
        }
        setImage(faceIdx, slideIdx) {
          const faces = document.querySelectorAll(`.slice-face.face-${faceIdx + 1} .slice-image`);
          const url = SLIDES[slideIdx].url;
          faces.forEach((img) => (img.style.backgroundImage = `url(${url})`));
        }
        loadImages() {
          SLIDES.forEach((_, i) => {
            if (i === this.idx) this.setImage(this.face, i);
          });
          this.updateText();
          this.resetProgress();
        }
        rotate() {
          if (this.anim) return;
          this.anim = true;
          const nextFace = (this.face + 1) % 4;
          this.setImage(nextFace, this.idx);
          const cubes = document.querySelectorAll(".slice-cube");
          cubes.forEach((c) => {
            c.classList.remove("rotate-0", "rotate-1", "rotate-2", "rotate-3");
            const cur = parseInt(c.dataset.rot || 0);
            const nxt = (cur + 1) % 4;
            c.dataset.rot = nxt;
            c.classList.add(`rotate-${nxt}`);
          });
          this.face = nextFace;
          setTimeout(() => (this.anim = false), 1200);
        }
        go(i) {
          if (this.anim || i === this.idx) return;
          this.idx = (i + SLIDES.length) % SLIDES.length;
          this.rotate();
          this.updateText();
          this.updateDots();
          this.updateThumbs();
          this.resetProgress();
        }
        next() { this.go(this.idx + 1); }
        prev() { this.go(this.idx - 1); }
        updateText() {
          const ov = document.getElementById("textOverlay");
          ov.classList.add("hiding");
          setTimeout(() => {
            document.getElementById("slideBadge").textContent = SLIDES[this.idx].badge;
            document.getElementById("slideTitle").textContent = SLIDES[this.idx].title;
            document.getElementById("slideDescription").textContent = SLIDES[this.idx].description;
            ov.classList.remove("hiding");
          }, 350);
        }
        buildDots() {
          const d = document.getElementById("dots");
          SLIDES.forEach((_, i) => {
            const dot = document.createElement("div");
            dot.className = "dot" + (i === 0 ? " active" : "");
            dot.onclick = () => this.go(i);
            d.appendChild(dot);
          });
        }
        updateDots() {
          document.querySelectorAll(".dot").forEach((d, i) => d.classList.toggle("active", i === this.idx));
        }
        buildThumbs() {
          const c = document.getElementById("thumbnails");
          SLIDES.forEach((s, i) => {
            const t = document.createElement("div");
            t.className = "thumbnail" + (i === 0 ? " active" : "");
            t.style.backgroundImage = `url(${s.thumb})`;
            t.onclick = () => this.go(i);
            c.appendChild(t);
          });
        }
        updateThumbs() {
          document.querySelectorAll(".thumbnail").forEach((t, i) => t.classList.toggle("active", i === this.idx));
        }
        resetProgress() {
          const pb = document.getElementById("progressBar");
          pb.classList.remove("active");
          void pb.offsetWidth;
          if (this.playing) pb.classList.add("active");
        }
        play() {
          this.timer = setInterval(() => { this.next(); }, 4500);
        }
        pause() {
          clearInterval(this.timer);
          this.timer = null;
        }
        toggle() {
          this.playing = !this.playing;
          const ic = document.getElementById("playIcon");
          if (this.playing) {
            this.play();
            ic.className = "fas fa-pause";
            document.getElementById("progressBar").classList.add("active");
          } else {
            this.pause();
            ic.className = "fas fa-play";
            document.getElementById("progressBar").classList.remove("active");
          }
        }
        events() {
          document.getElementById("nextArrow").onclick = () => {
            this.next();
            if (this.playing) { this.pause(); this.play(); }
          };
          document.getElementById("prevArrow").onclick = () => {
            this.prev();
            if (this.playing) { this.pause(); this.play(); }
          };
          document.getElementById("playPauseBtn").onclick = () => this.toggle();
        }
      }
      const slider = new SiOukliSlider();

      /* ══════════ NAVBAR SCROLL ══════════ */
      window.addEventListener("scroll", () => {
        document.getElementById("header").classList.toggle("scrolled", window.scrollY > 60);
        const links = document.querySelectorAll(".nav-link:not(.btn-login)");
        const sections = ["accueil", "services", "apropos", "contact"];
        let cur = "accueil";
        sections.forEach((id) => {
          const el = document.getElementById(id);
          if (el && window.scrollY >= el.offsetTop - 120) cur = id;
        });
        links.forEach((l) => l.classList.toggle("active", l.getAttribute("href") === "#" + cur));
      });

      /* ══════════ HAMBURGER ══════════ */
      document.getElementById("hamburger").onclick = function () {
        this.classList.toggle("active");
        document.getElementById("navMenu").classList.toggle("active");
      };

      /* ══════════ SERVICES TABS ══════════ */
      document.querySelectorAll(".service-tab").forEach((tab) => {
        tab.onclick = function () {
          document.querySelectorAll(".service-tab").forEach((t) => t.classList.remove("active"));
          document.querySelectorAll(".service-content").forEach((c) => c.classList.remove("active"));
          this.classList.add("active");
          document.getElementById(this.dataset.service).classList.add("active");
        };
      });

      /* ══════════ SCROLL REVEAL ══════════ */
      const obs = new IntersectionObserver(
        (entries) => { entries.forEach((e) => { if (e.isIntersecting) e.target.classList.add("visible"); }); },
        { threshold: 0.12 }
      );
      document.querySelectorAll(".reveal,.reveal-left,.reveal-right").forEach((el) => obs.observe(el));
    </script>
  </body>
</html>