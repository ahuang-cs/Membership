import React from "react";
import clsx from "clsx";
import Layout from "@theme/Layout";
import Link from "@docusaurus/Link";
import useDocusaurusContext from "@docusaurus/useDocusaurusContext";
import styles from "./index.module.css";

function HomepageHeader() {
  const { siteConfig } = useDocusaurusContext();
  return (
    <header className={clsx("hero hero--primary", styles.heroBanner)}>
      <div className="container">
        <h1 className="hero__title">{siteConfig.title}</h1>
        <p className="hero__subtitle">{siteConfig.tagline}</p>
        <div className={styles.buttons}>
          <Link
            className="button button--secondary button--lg"
            to="/docs/intro"
          >
            Get started?
          </Link>
        </div>
      </div>
    </header>
  );
}

export default function Home(): JSX.Element {
  const { siteConfig } = useDocusaurusContext();
  return (
    <Layout
      title={`Hello from ${siteConfig.title}`}
      description="Description will go into a meta tag in <head />"
    >
      <HomepageHeader />
      <main>
        <div className="container">
          <h2>
            Welcome to the Help and Support Documentation for the Membership
            System by Swimming Club Data Systems (SCDS).
          </h2>

          <h2>How to get more help</h2>

          <h3>Parents</h3>

          <p>
            Always speak to an official from your own club first. They will be
            able to access additional help and support from SCDS as required.
          </p>

          <p>
            In order to manage workload, parents are unable to access support
            from SCDS beyond the documentation provided here and on your club's
            website.
          </p>

          <h3>Club Officials</h3>

          <p>
            If you are unable to resolve your own or a parent's problem, contact
            SCDS by emailing support@myswimmingclub.co.uk quoting your name,
            club and position as well as giving as much detail about the problem
            as possible.
          </p>

          <h3>Self Hosted</h3>

          <p>
            If you're a self hosted user, support from SCDS is unavailable. You
            may wish to speak to your technical manager who will be able to{" "}
            <a href="https://github.com/Swimming-Club-Data-Systems/Membership">
              make bug reports to SCDS via GitHub Issues
            </a>
            .
          </p>
        </div>
      </main>
    </Layout>
  );
}
