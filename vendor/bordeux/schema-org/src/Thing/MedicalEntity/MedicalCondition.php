<?php

namespace Bordeux\SchemaOrg\Thing\MedicalEntity;

/**
 * Generated by bordeux/schema
 *
 * @author Krzysztof Bednarczyk <schema@bordeux.net>
 * @link http://schema.org/MedicalCondition
 *
 *
 * -------------------------------- AssociatedAnatomy ---------------------------------------------
 *
 * @property \Bordeux\SchemaOrg\Thing\MedicalEntity\AnatomicalSystem|\Bordeux\SchemaOrg\Thing\MedicalEntity\AnatomicalSystem[]|\Bordeux\SchemaOrg\Thing\MedicalEntity\SuperficialAnatomy|\Bordeux\SchemaOrg\Thing\MedicalEntity\SuperficialAnatomy[]|\Bordeux\SchemaOrg\Thing\MedicalEntity\AnatomicalStructure|\Bordeux\SchemaOrg\Thing\MedicalEntity\AnatomicalStructure[] associatedAnatomy
 *
 * @method \Bordeux\SchemaOrg\Thing\MedicalEntity\AnatomicalSystem|\Bordeux\SchemaOrg\Thing\MedicalEntity\AnatomicalSystem[]|\Bordeux\SchemaOrg\Thing\MedicalEntity\SuperficialAnatomy|\Bordeux\SchemaOrg\Thing\MedicalEntity\SuperficialAnatomy[]|\Bordeux\SchemaOrg\Thing\MedicalEntity\AnatomicalStructure|\Bordeux\SchemaOrg\Thing\MedicalEntity\AnatomicalStructure[] getAssociatedAnatomy() The anatomy of the underlying organ system or structures associated with this entity.
 *
 * @method MedicalCondition setAssociatedAnatomy(\Bordeux\SchemaOrg\Thing\MedicalEntity\AnatomicalSystem $associatedAnatomy ) setAssociatedAnatomy(\Bordeux\SchemaOrg\Thing\MedicalEntity\AnatomicalSystem[] $associatedAnatomy ) setAssociatedAnatomy(\Bordeux\SchemaOrg\Thing\MedicalEntity\SuperficialAnatomy $associatedAnatomy ) setAssociatedAnatomy(\Bordeux\SchemaOrg\Thing\MedicalEntity\SuperficialAnatomy[] $associatedAnatomy ) setAssociatedAnatomy(\Bordeux\SchemaOrg\Thing\MedicalEntity\AnatomicalStructure $associatedAnatomy ) setAssociatedAnatomy(\Bordeux\SchemaOrg\Thing\MedicalEntity\AnatomicalStructure[] $associatedAnatomy )The anatomy of the underlying organ system or structures associated with this entity.
 *
 *
 * -------------------------------- Cause ---------------------------------------------
 *
 * @property \Bordeux\SchemaOrg\Thing\MedicalEntity\MedicalCause|\Bordeux\SchemaOrg\Thing\MedicalEntity\MedicalCause[] cause
 *
 * @method \Bordeux\SchemaOrg\Thing\MedicalEntity\MedicalCause|\Bordeux\SchemaOrg\Thing\MedicalEntity\MedicalCause[] getCause() An underlying cause. More specifically, one of the causative agent(s) that are most directly responsible for the pathophysiologic process that eventually results in the occurrence.
 *
 * @method MedicalCondition setCause(\Bordeux\SchemaOrg\Thing\MedicalEntity\MedicalCause $cause ) setCause(\Bordeux\SchemaOrg\Thing\MedicalEntity\MedicalCause[] $cause )An underlying cause. More specifically, one of the causative agent(s) that are most directly responsible for the pathophysiologic process that eventually results in the occurrence.
 *
 *
 * -------------------------------- DifferentialDiagnosis ---------------------------------------------
 *
 * @property \Bordeux\SchemaOrg\Thing\MedicalEntity\MedicalIntangible\DDxElement|\Bordeux\SchemaOrg\Thing\MedicalEntity\MedicalIntangible\DDxElement[] differentialDiagnosis
 *
 * @method \Bordeux\SchemaOrg\Thing\MedicalEntity\MedicalIntangible\DDxElement|\Bordeux\SchemaOrg\Thing\MedicalEntity\MedicalIntangible\DDxElement[] getDifferentialDiagnosis() One of a set of differential diagnoses for the condition. Specifically, a closely-related or competing diagnosis typically considered later in the cognitive process whereby this medical condition is distinguished from others most likely responsible for a similar collection of signs and symptoms to reach the most parsimonious diagnosis or diagnoses in a patient.
 *
 * @method MedicalCondition setDifferentialDiagnosis(\Bordeux\SchemaOrg\Thing\MedicalEntity\MedicalIntangible\DDxElement $differentialDiagnosis ) setDifferentialDiagnosis(\Bordeux\SchemaOrg\Thing\MedicalEntity\MedicalIntangible\DDxElement[] $differentialDiagnosis )One of a set of differential diagnoses for the condition. Specifically, a closely-related or competing diagnosis typically considered later in the cognitive process whereby this medical condition is distinguished from others most likely responsible for a similar collection of signs and symptoms to reach the most parsimonious diagnosis or diagnoses in a patient.
 *
 *
 * -------------------------------- Epidemiology ---------------------------------------------
 *
 * @property string|string[] epidemiology
 *
 * @method string|string[] getEpidemiology() The characteristics of associated patients, such as age, gender, race etc.
 *
 * @method MedicalCondition setEpidemiology(string $epidemiology ) setEpidemiology(string[] $epidemiology )The characteristics of associated patients, such as age, gender, race etc.
 *
 *
 * -------------------------------- ExpectedPrognosis ---------------------------------------------
 *
 * @property string|string[] expectedPrognosis
 *
 * @method string|string[] getExpectedPrognosis() The likely outcome in either the short term or long term of the medical condition.
 *
 * @method MedicalCondition setExpectedPrognosis(string $expectedPrognosis ) setExpectedPrognosis(string[] $expectedPrognosis )The likely outcome in either the short term or long term of the medical condition.
 *
 *
 * -------------------------------- NaturalProgression ---------------------------------------------
 *
 * @property string|string[] naturalProgression
 *
 * @method string|string[] getNaturalProgression() The expected progression of the condition if it is not treated and allowed to progress naturally.
 *
 * @method MedicalCondition setNaturalProgression(string $naturalProgression ) setNaturalProgression(string[] $naturalProgression )The expected progression of the condition if it is not treated and allowed to progress naturally.
 *
 *
 * -------------------------------- Pathophysiology ---------------------------------------------
 *
 * @property string|string[] pathophysiology
 *
 * @method string|string[] getPathophysiology() Changes in the normal mechanical, physical, and biochemical functions that are associated with this activity or condition.
 *
 * @method MedicalCondition setPathophysiology(string $pathophysiology ) setPathophysiology(string[] $pathophysiology )Changes in the normal mechanical, physical, and biochemical functions that are associated with this activity or condition.
 *
 *
 * -------------------------------- PossibleComplication ---------------------------------------------
 *
 * @property string|string[] possibleComplication
 *
 * @method string|string[] getPossibleComplication() A possible unexpected and unfavorable evolution of a medical condition. Complications may include worsening of the signs or symptoms of the disease, extension of the condition to other organ systems, etc.
 *
 * @method MedicalCondition setPossibleComplication(string $possibleComplication ) setPossibleComplication(string[] $possibleComplication )A possible unexpected and unfavorable evolution of a medical condition. Complications may include worsening of the signs or symptoms of the disease, extension of the condition to other organ systems, etc.
 *
 *
 * -------------------------------- PossibleTreatment ---------------------------------------------
 *
 * @property \Bordeux\SchemaOrg\Thing\MedicalEntity\MedicalTherapy|\Bordeux\SchemaOrg\Thing\MedicalEntity\MedicalTherapy[] possibleTreatment
 *
 * @method \Bordeux\SchemaOrg\Thing\MedicalEntity\MedicalTherapy|\Bordeux\SchemaOrg\Thing\MedicalEntity\MedicalTherapy[] getPossibleTreatment() A possible treatment to address this condition, sign or symptom.
 *
 * @method MedicalCondition setPossibleTreatment(\Bordeux\SchemaOrg\Thing\MedicalEntity\MedicalTherapy $possibleTreatment ) setPossibleTreatment(\Bordeux\SchemaOrg\Thing\MedicalEntity\MedicalTherapy[] $possibleTreatment )A possible treatment to address this condition, sign or symptom.
 *
 *
 * -------------------------------- PrimaryPrevention ---------------------------------------------
 *
 * @property \Bordeux\SchemaOrg\Thing\MedicalEntity\MedicalTherapy|\Bordeux\SchemaOrg\Thing\MedicalEntity\MedicalTherapy[] primaryPrevention
 *
 * @method \Bordeux\SchemaOrg\Thing\MedicalEntity\MedicalTherapy|\Bordeux\SchemaOrg\Thing\MedicalEntity\MedicalTherapy[] getPrimaryPrevention() A preventative therapy used to prevent an initial occurrence of the medical condition, such as vaccination.
 *
 * @method MedicalCondition setPrimaryPrevention(\Bordeux\SchemaOrg\Thing\MedicalEntity\MedicalTherapy $primaryPrevention ) setPrimaryPrevention(\Bordeux\SchemaOrg\Thing\MedicalEntity\MedicalTherapy[] $primaryPrevention )A preventative therapy used to prevent an initial occurrence of the medical condition, such as vaccination.
 *
 *
 * -------------------------------- RiskFactor ---------------------------------------------
 *
 * @property \Bordeux\SchemaOrg\Thing\MedicalEntity\MedicalRiskFactor|\Bordeux\SchemaOrg\Thing\MedicalEntity\MedicalRiskFactor[] riskFactor
 *
 * @method \Bordeux\SchemaOrg\Thing\MedicalEntity\MedicalRiskFactor|\Bordeux\SchemaOrg\Thing\MedicalEntity\MedicalRiskFactor[] getRiskFactor() A modifiable or non-modifiable factor that increases the risk of a patient contracting this condition, e.g. age,  coexisting condition.
 *
 * @method MedicalCondition setRiskFactor(\Bordeux\SchemaOrg\Thing\MedicalEntity\MedicalRiskFactor $riskFactor ) setRiskFactor(\Bordeux\SchemaOrg\Thing\MedicalEntity\MedicalRiskFactor[] $riskFactor )A modifiable or non-modifiable factor that increases the risk of a patient contracting this condition, e.g. age,  coexisting condition.
 *
 *
 * -------------------------------- SecondaryPrevention ---------------------------------------------
 *
 * @property \Bordeux\SchemaOrg\Thing\MedicalEntity\MedicalTherapy|\Bordeux\SchemaOrg\Thing\MedicalEntity\MedicalTherapy[] secondaryPrevention
 *
 * @method \Bordeux\SchemaOrg\Thing\MedicalEntity\MedicalTherapy|\Bordeux\SchemaOrg\Thing\MedicalEntity\MedicalTherapy[] getSecondaryPrevention() A preventative therapy used to prevent reoccurrence of the medical condition after an initial episode of the condition.
 *
 * @method MedicalCondition setSecondaryPrevention(\Bordeux\SchemaOrg\Thing\MedicalEntity\MedicalTherapy $secondaryPrevention ) setSecondaryPrevention(\Bordeux\SchemaOrg\Thing\MedicalEntity\MedicalTherapy[] $secondaryPrevention )A preventative therapy used to prevent reoccurrence of the medical condition after an initial episode of the condition.
 *
 *
 * -------------------------------- SignOrSymptom ---------------------------------------------
 *
 * @property \Bordeux\SchemaOrg\Thing\MedicalEntity\MedicalSignOrSymptom|\Bordeux\SchemaOrg\Thing\MedicalEntity\MedicalSignOrSymptom[] signOrSymptom
 *
 * @method \Bordeux\SchemaOrg\Thing\MedicalEntity\MedicalSignOrSymptom|\Bordeux\SchemaOrg\Thing\MedicalEntity\MedicalSignOrSymptom[] getSignOrSymptom() A sign or symptom of this condition. Signs are objective or physically observable manifestations of the medical condition while symptoms are the subjective experienceof the medical condition.
 *
 * @method MedicalCondition setSignOrSymptom(\Bordeux\SchemaOrg\Thing\MedicalEntity\MedicalSignOrSymptom $signOrSymptom ) setSignOrSymptom(\Bordeux\SchemaOrg\Thing\MedicalEntity\MedicalSignOrSymptom[] $signOrSymptom )A sign or symptom of this condition. Signs are objective or physically observable manifestations of the medical condition while symptoms are the subjective experienceof the medical condition.
 *
 *
 * -------------------------------- Stage ---------------------------------------------
 *
 * @property \Bordeux\SchemaOrg\Thing\MedicalEntity\MedicalIntangible\MedicalConditionStage|\Bordeux\SchemaOrg\Thing\MedicalEntity\MedicalIntangible\MedicalConditionStage[] stage
 *
 * @method \Bordeux\SchemaOrg\Thing\MedicalEntity\MedicalIntangible\MedicalConditionStage|\Bordeux\SchemaOrg\Thing\MedicalEntity\MedicalIntangible\MedicalConditionStage[] getStage() The stage of the condition, if applicable.
 *
 * @method MedicalCondition setStage(\Bordeux\SchemaOrg\Thing\MedicalEntity\MedicalIntangible\MedicalConditionStage $stage ) setStage(\Bordeux\SchemaOrg\Thing\MedicalEntity\MedicalIntangible\MedicalConditionStage[] $stage )The stage of the condition, if applicable.
 *
 *
 * -------------------------------- Subtype ---------------------------------------------
 *
 * @property string|string[] subtype
 *
 * @method string|string[] getSubtype() A more specific type of the condition, where applicable, for example 'Type 1 Diabetes', 'Type 2 Diabetes', or 'Gestational Diabetes' for Diabetes.
 *
 * @method MedicalCondition setSubtype(string $subtype ) setSubtype(string[] $subtype )A more specific type of the condition, where applicable, for example 'Type 1 Diabetes', 'Type 2 Diabetes', or 'Gestational Diabetes' for Diabetes.
 *
 *
 * -------------------------------- TypicalTest ---------------------------------------------
 *
 * @property \Bordeux\SchemaOrg\Thing\MedicalEntity\MedicalTest|\Bordeux\SchemaOrg\Thing\MedicalEntity\MedicalTest[] typicalTest
 *
 * @method \Bordeux\SchemaOrg\Thing\MedicalEntity\MedicalTest|\Bordeux\SchemaOrg\Thing\MedicalEntity\MedicalTest[] getTypicalTest() A medical test typically performed given this condition.
 *
 * @method MedicalCondition setTypicalTest(\Bordeux\SchemaOrg\Thing\MedicalEntity\MedicalTest $typicalTest ) setTypicalTest(\Bordeux\SchemaOrg\Thing\MedicalEntity\MedicalTest[] $typicalTest )A medical test typically performed given this condition.
 *
 *
 */
 class MedicalCondition extends \Bordeux\SchemaOrg\Thing\MedicalEntity {

 }