{**
 * templates/submission/form/complete.tpl
 *
 * Copyright (c) 2003-2013 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * The submission process has been completed; notify the author.
 *}
<h2>{translate key="submission.submit.submissionComplete"}</h2>
<p>{translate key="submission.submit.submissionCompleteThanks" contextName=$context->getLocalizedName()}</p>
<div class="separator"></div>
<h3>{translate key="submission.submit.whatNext"}</h3>
<p>{translate key="submission.submit.whatNext.description"}</p>
<p>{translate key="submission.submit.whatNext.forNow"}</p>

<ul class="plain">
	{if $canExpedite}
		<li>{include file="linkAction/linkAction.tpl" action=$expediteLinkAction}</li>
	{/if}
	<li><a href={$reviewSubmissionUrl}>{translate key="submission.submit.whatNext.review"}</a></li>
	<li><a href={url page="submission" op="wizard"}>{translate key="submission.submit.whatNext.create"}</a></li>
	<li><a href={url page="dashboard" anchor="submissions"}>{translate key="submission.submit.whatNext.return"}</a></li>
</ul>
