<?php

namespace AmsterdamPHP\Bundle\SponsorBundle\Controller;

use AmsterdamPHP\Bundle\SponsorBundle\Entity\Package;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AmsterdamPHP\Bundle\SponsorBundle\Entity\Sponsor;
use AmsterdamPHP\Bundle\SponsorBundle\Form\SponsorType;

/**
 * Sponsor controller.
 *
 * @Route("/admin/sponsor")
 */
class SponsorController extends Controller
{

    /**
     * Lists all Sponsor entities.
     *
     * @Route("/", name="admin_sponsor")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AmsterdamPHPSponsorBundle:Sponsor')->findAll();

        return [
            'entities' => $entities,
        ];
    }
    /**
     * Creates a new Sponsor entity.
     *
     * @Route("/", name="admin_sponsor_create")
     * @Method("POST")
     * @Template("AmsterdamPHPSponsorBundle:Sponsor:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Sponsor();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_sponsor_show', array('id' => $entity->getId())));
        }

        return [
            'entity' => $entity,
            'form'   => $form->createView(),
        ];
    }

    /**
    * Creates a form to create a Sponsor entity.
    *
    * @param Sponsor $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Sponsor $entity)
    {
        $form = $this->createForm(new SponsorType(), $entity, [
            'action' => $this->generateUrl('admin_sponsor_create'),
            'method' => 'POST',
        ]);

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Sponsor entity.
     *
     * @Route("/new", name="admin_sponsor_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Sponsor();
        $form   = $this->createCreateForm($entity);

        return [
            'entity' => $entity,
            'form'   => $form->createView(),
        ];
    }

    /**
     * Finds and displays a Sponsor entity.
     *
     * @Route("/{id}", name="admin_sponsor_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AmsterdamPHPSponsorBundle:Sponsor')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Sponsor entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return [
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing Sponsor entity.
     *
     * @Route("/{id}/edit", name="admin_sponsor_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AmsterdamPHPSponsorBundle:Sponsor')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Sponsor entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return [
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
    * Creates a form to edit a Sponsor entity.
    *
    * @param Sponsor $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Sponsor $entity)
    {
        $form = $this->createForm(new SponsorType(), $entity, [
            'action' => $this->generateUrl('admin_sponsor_update', ['id' => $entity->getId()]),
            'method' => 'PUT',
        ]);

        $form->add('submit', 'submit', ['label' => 'Update']);

        return $form;
    }
    /**
     * Edits an existing Sponsor entity.
     *
     * @Route("/{id}", name="admin_sponsor_update")
     * @Method("PUT")
     * @Template("AmsterdamPHPSponsorBundle:Sponsor:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AmsterdamPHPSponsorBundle:Sponsor')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Sponsor entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_sponsor_edit', array('id' => $id)));
        }

        return [
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }
    /**
     * Deletes a Sponsor entity.
     *
     * @Route("/{id}", name="admin_sponsor_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AmsterdamPHPSponsorBundle:Sponsor')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Sponsor entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_sponsor'));
    }

    /**
     * @Route("/{id}/add-package/{type}", name="admin_sponsor_add_package")
     * @Route("/{id}/create-package/{type}", name="admin_sponsor_create_package")
     * @Template()
     */
    public function addPackageAction(Sponsor $sponsor, $type, Request $request)
    {
        $form = $this->createForm("amsphp_sponsor_package_$type", null, [
            'action' => $this->generateUrl('admin_sponsor_create_package', ['id' => $sponsor->getId(), 'type' => $type]),
            'method' => 'POST',
        ]);

        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var Package $package */
            $package = $form->getData();

            $package->setSponsor($sponsor);

            $em = $this->getDoctrine()->getManager();
            $em->persist($package);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_sponsor'));
        }

        return [
            'sponsor' => $sponsor,
            'form'   => $form->createView(),
        ];
    }

    /**
     * Creates a form to delete a Sponsor entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_sponsor_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
