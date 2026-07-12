<footer class="admin-project-footer" aria-label="Atribusi proyek">
    <span>Proyek Kewirausahaan Kelompok 1</span>
    <span class="admin-project-footer__dot" aria-hidden="true"></span>
    <span>Universitas UP45 Yogyakarta</span>
</footer>

<style>
    .admin-project-footer {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: .65rem;
        width: 100%;
        padding: 1.25rem 1rem 1.5rem;
        color: rgb(107 114 128);
        font-size: .6875rem;
        font-weight: 600;
        letter-spacing: .035em;
        text-align: center;
    }
    .admin-project-footer__dot {
        width: 3px;
        height: 3px;
        flex: 0 0 3px;
        border-radius: 999px;
        background: rgb(185 135 34);
        box-shadow: 0 0 0 3px rgb(185 135 34 / .1);
    }
    .dark .admin-project-footer { color: rgb(156 163 175); }
    @media (max-width: 640px) {
        .admin-project-footer { flex-direction: column; gap: .35rem; }
        .admin-project-footer__dot { display: none; }
    }
</style>
