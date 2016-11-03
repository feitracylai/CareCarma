<?php
namespace Aws\GameLift;

use Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon GameLift** service.
 *
 * @method \Aws\Result createAlias(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createAliasAsync(array $args = [])
 * @method \Aws\Result createBuild(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createBuildAsync(array $args = [])
 * @method \Aws\Result createFleet(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createFleetAsync(array $args = [])
 * @method \Aws\Result createGameSession(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createGameSessionAsync(array $args = [])
 * @method \Aws\Result createPlayerSession(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createPlayerSessionAsync(array $args = [])
 * @method \Aws\Result createPlayerSessions(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createPlayerSessionsAsync(array $args = [])
 * @method \Aws\Result deleteAlias(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteAliasAsync(array $args = [])
 * @method \Aws\Result deleteBuild(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteBuildAsync(array $args = [])
 * @method \Aws\Result deleteFleet(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteFleetAsync(array $args = [])
 * @method \Aws\Result deleteScalingPolicy(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteScalingPolicyAsync(array $args = [])
 * @method \Aws\Result describeAlias(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeAliasAsync(array $args = [])
 * @method \Aws\Result describeBuild(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeBuildAsync(array $args = [])
 * @method \Aws\Result describeEC2InstanceLimits(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeEC2InstanceLimitsAsync(array $args = [])
 * @method \Aws\Result describeFleetAttributes(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeFleetAttributesAsync(array $args = [])
 * @method \Aws\Result describeFleetCapacity(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeFleetCapacityAsync(array $args = [])
 * @method \Aws\Result describeFleetEvents(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeFleetEventsAsync(array $args = [])
 * @method \Aws\Result describeFleetPortSettings(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeFleetPortSettingsAsync(array $args = [])
 * @method \Aws\Result describeFleetUtilization(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeFleetUtilizationAsync(array $args = [])
 * @method \Aws\Result describeGameSessionDetails(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeGameSessionDetailsAsync(array $args = [])
 * @method \Aws\Result describeGameSessions(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeGameSessionsAsync(array $args = [])
 * @method \Aws\Result describePlayerSessions(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describePlayerSessionsAsync(array $args = [])
 * @method \Aws\Result describeScalingPolicies(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeScalingPoliciesAsync(array $args = [])
 * @method \Aws\Result getGameSessionLogUrl(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getGameSessionLogUrlAsync(array $args = [])
 * @method \Aws\Result listAliases(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listAliasesAsync(array $args = [])
 * @method \Aws\Result listBuilds(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listBuildsAsync(array $args = [])
 * @method \Aws\Result listFleets(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listFleetsAsync(array $args = [])
 * @method \Aws\Result putScalingPolicy(array $args = [])
 * @method \GuzzleHttp\Promise\Promise putScalingPolicyAsync(array $args = [])
 * @method \Aws\Result requestUploadCredentials(array $args = [])
 * @method \GuzzleHttp\Promise\Promise requestUploadCredentialsAsync(array $args = [])
 * @method \Aws\Result resolveAlias(array $args = [])
 * @method \GuzzleHttp\Promise\Promise resolveAliasAsync(array $args = [])
 * @method \Aws\Result updateAlias(array $args = [])
 * @method \GuzzleHttp\Promise\Promise updateAliasAsync(array $args = [])
 * @method \Aws\Result updateBuild(array $args = [])
 * @method \GuzzleHttp\Promise\Promise updateBuildAsync(array $args = [])
 * @method \Aws\Result updateFleetAttributes(array $args = [])
 * @method \GuzzleHttp\Promise\Promise updateFleetAttributesAsync(array $args = [])
 * @method \Aws\Result updateFleetCapacity(array $args = [])
 * @method \GuzzleHttp\Promise\Promise updateFleetCapacityAsync(array $args = [])
 * @method \Aws\Result updateFleetPortSettings(array $args = [])
 * @method \GuzzleHttp\Promise\Promise updateFleetPortSettingsAsync(array $args = [])
 * @method \Aws\Result updateGameSession(array $args = [])
 * @method \GuzzleHttp\Promise\Promise updateGameSessionAsync(array $args = [])
 */
class GameLiftClient extends AwsClient {}
